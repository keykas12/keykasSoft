<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Crear la vista v_stock
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_stock AS
            SELECT
                id_articulo,
                id_bodega,
                SUM(cantidad) AS stock
            FROM movimientos
            GROUP BY
                id_articulo,
                id_bodega
        ");
    }

    /**
     * Eliminar la vista v_stock
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_stock");
    }
};
