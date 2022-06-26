<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
Use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
Use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user' => 'required|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required |same:password',
            'dni' => 'required|unique:users',
            'tlf' => 'required',
            'email' => 'required|email|unique:users',]);

        $user = new User();
        $user->name = $request->name;
        $user->user = $request->user;
        $user->dni = $request->dni;
        $user->tlf = $request->tlf;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Se encripta la contraseña usando la función bcrypt().
        $user->save();
        return response()->json([
            'error' => false,
            'customer' => $user,
            'messages' => 'Registro completado'
        ], 200);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!JWTAuth::attempt($credentials)) {
                $response['data'] = null;
                $response['code'] = 500;
                $response['message'] = ' El Email o la Contraseña es incorrecta';
                return response()->json($response);
            }
        } catch (JWTException $e) {
            $response['data'] = null;
            $response['code'] = 500;
            $response['message'] = 'No se pudo crear token';
            return response()->json($response);
        }
        $user = auth()->user();
        $data['token'] = auth()->claims([
            'id' => $user->id,
            'email' => $user->email,
        ])->attempt($credentials);

        $response['data'] = $data;
        $response['status'] = 1;
        $response['code'] = 200;
        $response['message'] = 'Login con exito';
        return response()->json($response);
    }

    public function actualizar($id, Request $request)
    {


        $request->validate([
            'name' => 'required',
            'user' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required |same:password',
            'dni' => 'required',
            'tlf' => 'required',
            'email' => 'required|email',]);
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->user = $request->user;
        $user->dni = $request->dni;
        $user->tlf = $request->tlf;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Se encripta la contraseña usando la función bcrypt().
        $user->save();
        return response()->json([
            'error' => false,
            'customer' => $user,
            'messages' => 'Actualización completa'
        ], 200);
    }

    public function eliminar($id)
    {
        $customer = User::find($id);
        if (is_null($customer)) {
            return response()->json([
                'error' => true,
                'message' => "id usuario no existe",
            ], 404);
        }
        $customer->delete();
        return response()->json([
            'error' => false,
            'message' => "Se ha borrado el usuario",
        ], 200);
    }

    public function mostrar()
    {
        $users = DB::table('users')->get();
        if ($users->count() === 0) {
            return response()->json([
                'error' => true,
                'message' => "No hay Usuarios",
            ], 404);
        }

        return $users;

    }
}
