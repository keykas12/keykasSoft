<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Movimiento;
use App\Http\Requests\StoreMovimientoRequest;

class MovimientoController extends Controller
{
    /**
     * LISTADO de movimientos
     */
    public function index(Request $r)
    {
        $q = $r->get('q'); // búsqueda

        $items = DB::table('movimientos as m')
            ->join('articulos as a', 'a.id_articulo', '=', 'm.id_articulo')
            ->join('bodegas as b', 'b.id_bodega', '=', 'm.id_bodega')
            ->leftJoin('pt_variantes as v', 'v.id_pt_var', '=', 'm.id_pt_var')
            ->select(
                'm.*',
                'a.codigo as art_codigo',
                'a.nombre as art_nombre',
                'a.tipo as art_tipo',
                'b.nombre as bodega',
                'v.codigo_variante as variante' 
            )
            ->when($q, fn($x) => $x->where(function ($w) use ($q) {
                $w->where('a.codigo', 'ilike', "%$q%")
                  ->orWhere('a.nombre', 'ilike', "%$q%")
                  ->orWhere('m.doc_ref', 'ilike', "%$q%");
            }))
            ->orderByDesc('m.fecha')
            ->orderByDesc('m.id_mov')
            ->paginate(15)
            ->appends(request()->query());

        return view('movimientos.index', compact('items', 'q'));
    }

    /**
     * FORMULARIO de creación
     */
    public function create()
    {
        // Tipos de movimiento disponibles
        $tipos = [
            'COMPRA_MP'      => 'Compra de materia prima',
            'CONSUMO_MP'     => 'Consumo de materia prima',
            'PRODUCCION_PT'  => 'Producción de producto terminado',
            'VENTA_PT'       => 'Venta de producto terminado',
            'AJUSTE_ENTRADA' => 'Ajuste de entrada',
            'AJUSTE_SALIDA'  => 'Ajuste de salida',
        ];

        // Cargar bodegas y artículos por tipo
        $bodegasMp   = DB::table('bodegas')->where('tipo', 'MP')->where('activo', true)->orderBy('nombre')->get();
        $bodegasPt   = DB::table('bodegas')->where('tipo', 'PT')->where('activo', true)->orderBy('nombre')->get();
        $articulosMp = DB::table('articulos')->where('tipo', 'MP')->where('estado', 'A')->orderBy('nombre')->get();
        $articulosPt = DB::table('articulos')->where('tipo', 'PT')->where('estado', 'A')->orderBy('nombre')->get();

        return view('movimientos.create', [
            'tipos'       => $tipos,
            'bodegasMp'   => $bodegasMp,
            'bodegasPt'   => $bodegasPt,
            'articulosMp' => $articulosMp,
            'articulosPt' => $articulosPt,
        ]);
    }

    /**
     * GUARDAR movimiento nuevo
     */
    public function store(StoreMovimientoRequest $req)
    {
        // Ya viene validado por StoreMovimientoRequest
        $data = $req->validated();
        $t    = $data['tipo'];

        // Escoger IDs correctos según el tipo (MP o PT)
        if (in_array($t, ['COMPRA_MP', 'CONSUMO_MP'], true)) {
            $idBodega   = $req->input('id_bodega_mp');
            $idArticulo = $req->input('id_articulo_mp');
        } else {
            $idBodega   = $req->input('id_bodega_pt');
            $idArticulo = $req->input('id_articulo_pt');
        }

        // Firmar cantidad: positiva para entradas, negativa para salidas
        $salidas  = ['CONSUMO_MP', 'VENTA_PT', 'AJUSTE_SALIDA'];
        $cantidad = (int) $data['cantidad'];
        $cantidad = in_array($t, $salidas, true)
            ? -abs($cantidad)
            :  abs($cantidad);

        Movimiento::create([
            'fecha'       => $data['fecha'] ?? now(),
            'tipo'        => $t,
            'id_bodega'   => $idBodega,
            'id_articulo' => $idArticulo,
            'id_pt_var'   => $data['id_pt_var'] ?? null,
            'cantidad'    => $cantidad,
            'doc_ref'     => $data['doc_ref'] ?? null,
            'observacion' => $data['observacion'] ?? null,
        ]);

        return redirect()
            ->route('movimientos.index')
            ->with('ok', '✅ Movimiento registrado correctamente.');
    }
}
