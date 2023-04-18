<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validar
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // almacenamos credenciales
        $credenciales = request(["email", "password"]);


        // Verificamos si el usuario esta registrado
        $usuarioQuery = User::where('email', '=', $request->email)->first();


        //validamos si el usuario tiene permiso para acceder / SI-NO
        if (!$usuarioQuery) {
            return response()->json(["mensaje" => "No Autorizado"], 401);
        } else {

            if ($usuarioQuery->permiso == true) {
                // return response()->json(["usuario" => $usuarioQuery]);
                // return response()->json(["usuario" => $usuarioQuery, 'Permiso' => 'SI tiene permiso']);


                // Intentamos hacer el login
                if (!Auth::attempt($credenciales)) {
                    return response()->json([
                        "mensaje" => "No Autorizado Incorrect"
                    ], 401);
                }

                // generar tonken para el usuario logeado
                $usuario = $request->user();
                $tokenResult = $usuario->createToken("login");
                $token = $tokenResult->plainTextToken;

                // Encriptamos los datos del usuario
                $userProtect = base64_encode($usuario);

                // responder con datos encriptados del usuario y token generado
                return response()->json([
                    "access_token" => base64_encode($token),
                    "token_type" => "Bearer",
                    "usuario" => base64_encode($usuario)
                ]);
            } else {
                return response()->json(["usuario" => $usuarioQuery, 'Permiso' => 'NO tiene permiso'], 403);
            }
        }

        //return $request->user();




    }

    public function registro(Request $request)
    {
        //validar
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        // registro
        $usuario = new User();

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);

        $usuario->save();

        // responder
        return response()->json(["mensaje" => "Usuario Registrado"], 201);
    }

    public function logout(Request $request)
    {
        // Obtenemos el id del token
        // $id = strtok($request->token, "|");

        // A MI FORMA :V
        /* DB::table('personal_access_tokens')->delete($id); */

        // Revoke a specific token...
        /* $request->user()->tokens()->where('id', $id)->delete(); */

        // Revocar el token que se usó para autenticar la solicitud actual...
        $request->user()->currentAccessToken()->delete();

        // $request->user()->tokens()->delete()->where('id', $idToken); //Elimina todos los tokens del usuario

        return response()->json([
            "mensaje" => "Logout"
        ]);
    }

    public function perfil(Request $request)
    {
        // return $request->user();
        // return response()->json($request->user());
        // return response()->json(Auth::user());


        $usuario = $request->user();

        $userProtect = base64_encode($usuario);


        return response()->json(["user" => $userProtect], 200);
    }

    public function tokenVerify(Request $request)
    {
        // Obtenemos el id del token
        $id = strtok($request->token, "|");

        $tokenActual = $request->token;

        $respuesta = '';

        $q = DB::table('personal_access_tokens')
            ->select()
            ->where('id', '=', $id)
            ->first();

        if ($q == NULL) {
            $respuesta = null;
            // return false;
            return response()->json(["Token" => "$tokenActual", "Datos" => $respuesta]);
        } else {
            $respuesta = $q;
            // return true;
            // $r = $q->token;
            return response()->json(["Token" => "$tokenActual", "Datos" => $respuesta]);
        }
    }
}