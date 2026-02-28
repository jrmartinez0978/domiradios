<?php

namespace App\Http\Controllers;

use App\Models\Radio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RatingController extends Controller
{
    /**
     * Guarda la valoración de una emisora
     */
    public function rateRadio(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'radio_id' => 'required|exists:radios,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        $radioId = $request->input('radio_id');
        $rating = $request->input('rating');

        // Recuperar la emisora
        $radio = Radio::findOrFail($radioId);
        
        // Recuperar las valoraciones actuales almacenadas en la sesión
        $ratings = Session::get('user_ratings', []);
        
        // Guardar la nueva valoración en la sesión
        $ratings[$radioId] = $rating;
        Session::put('user_ratings', $ratings);
        
        // Calcular el nuevo promedio de valoración
        $totalRatings = count($ratings);
        $currentAverage = $radio->rating ?? 0;
        
        // Si es la primera valoración en la base de datos, simplemente usar la nueva
        if ($currentAverage == 0) {
            $newRating = $rating;
        } else {
            // Promedio ponderado para dar más peso a la valoración existente
            // (que es el promedio de todas las valoraciones anteriores)
            $adminWeight = 0.7; // Peso para las valoraciones existentes (admin)
            $userWeight = 0.3;  // Peso para las valoraciones de usuarios
            
            $newRating = ($currentAverage * $adminWeight) + ($rating * $userWeight);
        }
        
        // Actualizar la valoración en la base de datos
        $radio->rating = $newRating;
        $radio->save();
        
        // Retornar una respuesta JSON con la nueva valoración
        return response()->json([
            'success' => true,
            'message' => '¡Gracias por tu valoración!',
            'new_rating' => $newRating,
            'radio_id' => $radioId
        ]);
    }
    
    /**
     * Obtiene la valoración del usuario para una emisora específica
     */
    public function getUserRating($radioId)
    {
        // Validate radioId is a positive integer
        $radioId = (int) $radioId;
        if ($radioId <= 0) {
            return response()->json(['error' => 'ID de emisora inválido'], 400);
        }

        $ratings = Session::get('user_ratings', []);

        // Verificar si el usuario ha valorado esta emisora
        $userRating = $ratings[$radioId] ?? 0;

        return response()->json([
            'rating' => $userRating
        ]);
    }
}
