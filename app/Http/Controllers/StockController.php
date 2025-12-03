<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Devuelve el stock actual de un artículo en una bodega.
     *
     * GET /inventario/stock?articulo=1&bodega=2
     * Respuesta JSON:
     * { "articulo": 1, "bodega": 2, "stock": 123 }
     */
    public function stock(Request $r)
    {
        $idArticulo = (int) $r->query('articulo');  // ?articulo=1
        $idBodega   = (int) $r->query('bodega');    // ?bodega=2

        // Validar parámetros
        if (!$idArticulo || !$idBodega) {
            return response()->json([
                'error' => 'Parámetros inválidos',
            ], 400);
        }

        // Consultar vista v_stock (sin esquema zap)
        // Asumo columnas: id_articulo, id_bodega, stock
        $row = DB::table('v_stock')
            ->where('id_articulo', $idArticulo)
            ->where('id_bodega',  $idBodega)
            ->first();

        return response()->json([
            'articulo' => $idArticulo,
            'bodega'   => $idBodega,
            'stock'    => (int) ($row->stock ?? 0),
        ]);
    }
}
