<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    
    public function login()
    {
        return response()->json([
            'success' => true,
            'datos' => [
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Login disponible',
            'status' => 200
        ], 200);
    }


    
    public function iniciarSesion(Request $request)
    {
        $email = $request->correo;
        $clave = $request->passwordd;

        $datosUsuario = DB::select(
            'CALL sp_Usuario_Login(?, ?)',
            [$email, $clave]
        );

        $encontrado = count($datosUsuario) > 0;
        $codigoEstado = $encontrado ? 200 : 404;

        return response()->json([
            'success' => $encontrado,
            'usuario' => $encontrado ? $datosUsuario : null,
            'message' => $encontrado
                ? 'Sesión iniciada correctamente'
                : 'Datos de acceso incorrectos',
            'status' => $codigoEstado
        ], $codigoEstado);
    }


    
    public function registrar()
    {
        return response()->json([
            'success' => true,
            'datosRegistro' => [
                'nombres' => null,
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Registro disponible',
            'status' => 200
        ], 200);
    }


    
    public function guardar(Request $request)
    {
        $nombreUsuario = $request->nombres;
        $email = $request->correo;
        $clave = $request->passwordd;

        DB::statement(
            'CALL sp_Usuario_Guardar(?, ?, ?)',
            [$nombreUsuario, $email, $clave]
        );

        $datosUsuario = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$email]
        );

        $registrado = count($datosUsuario) > 0;
        $codigoEstado = $registrado ? 200 : 404;

        return response()->json([
            'success' => $registrado,
            'usuario' => $registrado ? $datosUsuario : null,
            'message' => $registrado
                ? 'Registro realizado correctamente'
                : 'No fue posible registrar al usuario',
            'status' => $codigoEstado
        ], $codigoEstado);
    }


    
    public function recuperar()
    {
        return response()->json([
            'success' => true,
            'datosRecuperacion' => [
                'correo' => null
            ],
            'message' => 'Recuperación de contraseña disponible',
            'status' => 200
        ], 200);
    }


    
    public function enviarCodigo(Request $request)
    {
        $email = $request->correo;

        $datosUsuario = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$email]
        );

        if (count($datosUsuario) == 0) {
            return response()->json([
                'success' => false,
                'codigo' => null,
                'message' => 'El correo ingresado no está registrado',
                'status' => 404
            ], 404);
        }

        $codigoGenerado = DB::select(
            'CALL sp_Usuario_Codigo(?)',
            [$email]
        );

        $generado = count($codigoGenerado) > 0;
        $codigoEstado = $generado ? 200 : 404;

        return response()->json([
            'success' => $generado,
            'codigo' => $generado ? $codigoGenerado : null,
            'message' => $generado
                ? 'Código creado correctamente'
                : 'No se pudo crear el código',
            'status' => $codigoEstado
        ], $codigoEstado);
    }


    
    public function validar()
    {
        return response()->json([
            'success' => true,
            'datosValidacion' => [
                'codigo' => null
            ],
            'message' => 'Validación de código disponible',
            'status' => 200
        ], 200);
    }


    
    public function validarCodigo(Request $request)
    {
        $codigoIngresado = $request->codigo;

        $resultadoCodigo = DB::select(
            'CALL sp_Usuario_Validar(?)',
            [$codigoIngresado]
        );

        $esValido = count($resultadoCodigo) > 0;
        $codigoEstado = $esValido ? 200 : 404;

        return response()->json([
            'success' => $esValido,
            'codigo' => $esValido ? $resultadoCodigo : null,
            'message' => $esValido
                ? 'El código es válido'
                : 'El código ingresado no es válido',
            'status' => $codigoEstado
        ], $codigoEstado);
    }


    
    public function passwordd()
    {
        return response()->json([
            'success' => true,
            'datosPassword' => [
                'correo' => null,
                'passwordd' => null
            ],
            'message' => 'Cambio de contraseña disponible',
            'status' => 200
        ], 200);
    }


   
    public function actualizarPasswordd(Request $request)
    {
        $email = $request->correo;
        $nuevaClave = $request->passwordd;

        DB::statement(
            'CALL sp_Usuario_UpdatePasswordd(?, ?)',
            [$email, $nuevaClave]
        );

        $datosUsuario = DB::select(
            'SELECT * FROM Usuario WHERE Correo = ?',
            [$email]
        );

        $actualizado = count($datosUsuario) > 0;
        $codigoEstado = $actualizado ? 200 : 404;

        return response()->json([
            'success' => $actualizado,
            'usuario' => $actualizado ? $datosUsuario : null,
            'message' => $actualizado
                ? 'Contraseña modificada correctamente'
                : 'No se encontró el usuario',
            'status' => $codigoEstado
        ], $codigoEstado);
    }
}

