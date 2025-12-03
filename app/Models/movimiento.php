<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    // Conexión (pgsql por tu entorno)
    protected $connection = 'pgsql';

    // Tabla (sin esquema zap)
    protected $table = 'movimientos';
    protected $primaryKey = 'id_mov';

    // PK bigint identity (autoincremental)
    public $incrementing = true;
    protected $keyType = 'int';

    // La tabla usa 'fecha', no created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'id_bodega',
        'id_articulo',
        'id_pt_var',
        'tipo',
        'cantidad',
        'doc_ref',
        'observacion',
    ];

    protected $casts = [
        'id_mov'      => 'integer',
        'fecha'       => 'datetime',
        'id_bodega'   => 'integer',
        'id_articulo' => 'integer',
        'id_pt_var'   => 'integer',
        'cantidad'    => 'integer',
    ];

    // Relaciones básicas (las usaremos más adelante)
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'id_articulo');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'id_bodega');
    }

    public function variantePt()
    {
        return $this->belongsTo(PTVariante::class, 'id_pt_var');
    }
}

