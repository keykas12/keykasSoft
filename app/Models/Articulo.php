<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'articulos';
    protected $primaryKey = 'id_articulo';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'tipo','codigo','nombre','id_unidad','id_mp_tipo','id_marca',
        'id_categoria_pt','id_subcategoria_pt','estado',
    ];

    protected $casts = [
        'id_articulo'        => 'integer',
        'id_unidad'          => 'integer',
        'id_mp_tipo'         => 'integer',
        'id_marca'           => 'integer',
        'id_categoria_pt'    => 'integer',
        'id_subcategoria_pt' => 'integer',
        'creado_en'          => 'datetime',
        'actualizado_en'     => 'datetime',
    ];
}
