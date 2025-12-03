<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PTVarianteController extends Controller
{
    /**
     * Devuelve las variantes de un artículo PT en JSON (para AJAX)
     */
    public function porArticulo($articuloId)
    {
        $vars = DB::table('pt_variantes')
            ->where('id_articulo_pt', $articuloId)
            ->orderBy('codigo_variante')
            ->get([
                'id_pt_var',
                'codigo_variante',
            ]);

        return response()->json($vars);
    }
}
