<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageVisitController extends Controller
{
    public function registerVisit(Request $request)
    {
        $url = $request->input('url');
        $user = Auth::user();

        // Buscar la página en la tabla `pages`
        $page = Page::firstOrCreate(
            ['url' => $url],
            ['nombre' => $request->input('nombre', 'Página desconocida')]
        );

        // Incrementar el contador global de visitas en la tabla `pages`
        $page->increment('visitas');

        // Comprobar si el usuario ya tiene un registro en la tabla intermedia
        $pivot = $page->users()->where('user_id', $user->id)->first();

        if ($pivot) {
            // Si ya existe, actualizar el contador de visitas
            $pivot->pivot->increment('visitas');
        } else {
            // Si no existe, crear un nuevo registro en la tabla intermedia con visitas = 1
            $page->users()->attach($user->id, ['visitas' => 1]);
        }

        // Obtener las visitas globales y del usuario
        $userVisits = $page->users()->where('user_id', $user->id)->first()->pivot->visitas;

        return response()->json([
            'page_visits' => $page->visitas,
            'user_visits' => $userVisits
        ]);
    }
}
