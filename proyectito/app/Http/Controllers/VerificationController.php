<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /**
     * Genera el código, lo guarda en caché y simula el envío al correo.
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Generar código aleatorio de 6 dígitos
        $code = (string) random_int(100000, 999999);

        // Guardar en la Caché por 10 minutos
        Cache::put('otp_' . $email, $code, now()->addMinutes(10));

        // Enviar el correo (se escribirá en storage/logs/laravel.log)
        Mail::to($email)->send(new VerificationCodeMail($code));

        return response()->json([
            'status'  => true,
            'message' => 'El código de verificación ha sido generado y guardado en el log.',
        ]);
    }

    /**
     * Valida el código ingresado por el usuario.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);

        $email = $request->input('email');
        $inputCode = $request->input('code');

        // Obtener el código guardado en Caché
        $cachedCode = Cache::get('otp_' . $email);

        if (!$cachedCode || $cachedCode !== $inputCode) {
            return response()->json([
                'status'  => false,
                'message' => 'El código ingresado es incorrecto o ha expirado.',
            ], 422);
        }

        // Eliminar el código para que no vuelva a usarse
        Cache::forget('otp_' . $email);

        return response()->json([
            'status'  => true,
            'message' => 'Código verificado con éxito.',
        ]);
    }
}