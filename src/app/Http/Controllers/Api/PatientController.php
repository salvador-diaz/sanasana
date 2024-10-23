<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    public function create(Request $request) {
        try {
        $validated = $request->validate([
            'name' => 'bail|required|string|min:5|max:255',
            'email' => 'bail|required|email|string|max:255|uique:users,email',
            'address' => 'bail|required|string|min:10|max:255',
            // Phone number regex following E.164 standard
            'phone' => 'bail|required|min:8|regex:/^\+[1-9]\d{0,14}|$/',
            'document-photo' => 'bail|required|mimes:jpg,jpeg,png|max:2048',
        ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed:',
                'errors' => $e->validator->errors(),
            ], 400);
        }

        // Si la validación pasa, continúa con la lógica
        return response()->json(['message' => 'Datos validados correctamente.']);
    }
}
