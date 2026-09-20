<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function verLogin()
    {
        return response()->json([
            'success' => true,
            'correo' => null,
            'passwordd' => null,
            'message' => 'Login disponible',
            'status' => 200
        ]);
    }


    public function entrar(Request $request)
    {
        $correo = $request->correo;
        $pass = $request->passwordd;

        $user = DB::select(
            'CALL sp_Usuario_Login(?, ?)',
            [$correo, $pass]
        );

        if (count($user) > 0) {
            return response()->json([
                'success' => true,
                'usuario' => $user,
                'message' => 'Inicio correcto',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'usuario' => null,
            'message' => 'Correo o contraseña incorrectos',
            'status' => 404
        ], 404);
    }


    public function verRegistro()
    {
        return response()->json([
            'success' => true,
            'nombres' => null,
            'correo' => null,
            'passwordd' => null,
            'message' => 'Registro disponible',
            'status' => 200
        ]);
    }


    public function crear(Request $request)
    {
        $nombre = $request->nombres;
        $correo = $request->correo;
        $pass = $request->passwordd;

        DB::statement(
            'CALL sp_Usuario_Guardar(?, ?, ?)',
            [$nombre, $correo, $pass]
        );

        $user = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$correo]
        );

        return response()->json([
            'success' => count($user) > 0,
            'usuario' => $user,
            'message' => 'Registro procesado',
            'status' => 200
        ], 200);
    }


    public function verRecuperar()
    {
        return response()->json([
            'success' => true,
            'correo' => null,
            'message' => 'Recuperación disponible',
            'status' => 200
        ]);
    }


    public function codigo(Request $request)
    {
        $correo = $request->correo;

        $user = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$correo]
        );

        if (count($user) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Correo no registrado',
                'status' => 404
            ], 404);
        }

        $resultado = DB::select(
            'CALL sp_Usuario_Codigo(?)',
            [$correo]
        );

        return response()->json([
            'success' => true,
            'codigo' => $resultado,
            'message' => 'Código generado',
            'status' => 200
        ], 200);
    }


    public function verCodigo()
    {
        return response()->json([
            'success' => true,
            'codigo' => null,
            'message' => 'Validación disponible',
            'status' => 200
        ]);
    }


    public function comprobar(Request $request)
    {
        $codigo = $request->codigo;

        $resultado = DB::select(
            'CALL sp_Usuario_Validar(?)',
            [$codigo]
        );

        if (count($resultado) > 0) {
            return response()->json([
                'success' => true,
                'codigo' => $resultado,
                'message' => 'Código correcto',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'codigo' => null,
            'message' => 'Código incorrecto',
            'status' => 404
        ], 404);
    }


    public function verPassword()
    {
        return response()->json([
            'success' => true,
            'correo' => null,
            'passwordd' => null,
            'message' => 'Cambio de contraseña disponible',
            'status' => 200
        ]);
    }


    public function cambiarPass(Request $request)
    {
        $correo = $request->correo;
        $pass = $request->passwordd;

        DB::statement(
            'CALL sp_Usuario_UpdatePasswordd(?, ?)',
            [$correo, $pass]
        );

        $user = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$correo]
        );

        if (count($user) > 0) {
            return response()->json([
                'success' => true,
                'usuario' => $user,
                'message' => 'Contraseña actualizada',
                'status' => 200
            ], 200);
        }

        return response()->json([
            'success' => false,
            'usuario' => null,
            'message' => 'Usuario no encontrado',
            'status' => 404
        ], 404);
    }
}