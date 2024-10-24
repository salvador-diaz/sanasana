<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Notifications\PatientRegistration;

class PatientController extends Controller
{
    public function create(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'bail|required|string|min:5|max:255',
                'email' => 'bail|required|email|string|max:255|unique:users,email',
                'address' => 'bail|required|string|min:10|max:255',
                // Phone number regex following E.164 standard
                'phone' => ['bail', 'required', 'min:8', 'regex:/^\+[1-9]\d{0,14}|$/'],
                'document-photo' => 'bail|required|mimes:jpg,jpeg,png|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed:',
                'errors' => $e->validator->errors(),
            ], 400);
        }

        $photoPath = $request->file('document-photo')->store('document_photos');
        if (!$photoPath)
            return response()->json(['message' => 'File storage failed. No data was saved.'], 500);

        // Save patient to database using Model
        try {
            $newPatient = Patient::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'document_photo_url' => $photoPath,
            ]);
        } catch (\Exception $e) {
            // On failure, previously saved document photo will be deleted
            Storage::delete($photoPath);
            return response()->json(['message' => 'Database storage failed. No data was saved.'], 500);
        }

        // Send notification
        $newPatient->notify(new PatientRegistration($newPatient));

        return response()->json(['message' => 'Patient was registered successfully and will recieve a notification shortly.']);
    }

    public function list() {
        return response()->json(Patient::all());
    }
}
