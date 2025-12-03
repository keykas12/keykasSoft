<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Http\Requests\ArticuloStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->input('q');

        $items = Articulo::query()
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nombre', 'ILIKE', "%{$q}%")
                        ->orWhere('codigo', 'ILIKE', "%{$q}%");
                });
            })
            ->orderBy('id_articulo')
            ->paginate(10)
            ->withQueryString();

        return view('articulos.index', compact('items', 'q'));
    }
public function edit(Articulo $articulo)
{
    $unidades      = DB::table('unidades')->select('id_unidad','nombre','codigo')->orderBy('nombre')->get();
    $mpTipos       = DB::table('mp_tipos')->select('id_mp_tipo','nombre')->orderBy('nombre')->get();
    $marcas        = DB::table('marcas')->select('id_marca','nombre')->orderBy('nombre')->get();
    $categorias    = DB::table('categorias_pt')->select('id_categoria_pt','nombre')->orderBy('nombre')->get();
    $subcategorias = DB::table('subcategorias_pt')->select('id_subcategoria_pt','id_categoria_pt','nombre')->orderBy('nombre')->get();

    return view('articulos.edit', compact(
        'articulo', 'unidades', 'mpTipos', 'marcas', 'categorias', 'subcategorias'
    ));
}

    public function create()
    {
        // Catálogos (sin esquema zap.)
        $unidades      = DB::table('unidades')->select('id_unidad','nombre','codigo')->orderBy('nombre')->get();
        $mpTipos       = DB::table('mp_tipos')->select('id_mp_tipo','nombre')->orderBy('nombre')->get();
        $marcas        = DB::table('marcas')->select('id_marca','nombre')->orderBy('nombre')->get();
        $categorias    = DB::table('categorias_pt')->select('id_categoria_pt','nombre')->orderBy('nombre')->get();
        $subcategorias = DB::table('subcategorias_pt')->select('id_subcategoria_pt','id_categoria_pt','nombre')->orderBy('nombre')->get();

        return view('articulos.create', compact('unidades','mpTipos','marcas','categorias','subcategorias'));
    }

    public function store(ArticuloStoreRequest $req)
    {
        $data = $req->validated();

        // Valor por defecto coherente con las reglas nuevas
        $data['estado'] = $data['estado'] ?? 'A';

        // Normaliza campos según el tipo
        if ($data['tipo'] === 'MP') {
            $data['id_categoria_pt'] = null;
            $data['id_subcategoria_pt'] = null;
        } else { // PT
            $data['id_mp_tipo'] = null;
        }

        Articulo::create($data);

        return redirect()
            ->route('articulos.index')
            ->with('ok', '✅ Artículo creado correctamente.');
    }
}
