<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radio;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
    {
        // Validación básica de los campos
        $validationRules = [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'bitrate' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'link_radio' => 'required|url|max:255',
            'tags' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Aplicamos validación básica
        $request->validate($validationRules);
        
        // Verificar si ya existe una emisora con el mismo nombre o URL
        $existingRadio = Radio::where('name', $request->name)
            ->orWhere('link_radio', $request->link_radio)
            ->first();
            
        if ($existingRadio) {
            return back()
                ->withInput()
                ->withErrors(['duplicate' => 'Ya existe una emisora con el mismo nombre o URL. Por favor, verifica la información.']);
        }

        // Guardar la imagen si se ha subido
        $imagePath = null;
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('emisoras', 'public');
        }

        // Crear un slug a partir del nombre
        $slug = Str::slug($request->name);

        // Crear la emisora pero mantenerla inactiva hasta que un administrador la revise
        $radio = new Radio();
        $radio->name = $request->name;
        $radio->slug = $slug;
        $radio->bitrate = $request->bitrate;
        $radio->tags = $request->tags;
        $radio->img = $imagePath;
        $radio->link_radio = $request->link_radio;
        $radio->url_website = $request->url_website ?? '';
        $radio->url_facebook = $request->url_facebook ?? '';
        $radio->url_twitter = $request->url_twitter ?? '';
        $radio->url_instagram = $request->url_instagram ?? '';
        $radio->description = $request->description ?? '';
        $radio->type_radio = 'streaming';
        $radio->source_radio = 'user_submitted';
        $radio->user_agent_radio = $request->user_agent ?? 'Mozilla/5.0';
        $radio->isActive = false; // Por defecto inactiva hasta que un administrador la apruebe
        $radio->isFeatured = false;
        
        // Guardar la emisora
        try {
            $radio->save();
            return back()->with('success', 'Tu emisora ha sido enviada correctamente. Revisaremos la información y la activaremos pronto.');
        } catch (\Exception $e) {
            // En caso de error, mostrar un mensaje amigable
            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente.']);
        }
    }
}
