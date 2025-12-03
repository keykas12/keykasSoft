<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreMovimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipos = [
            'COMPRA_MP',
            'CONSUMO_MP',
            'PRODUCCION_PT',
            'VENTA_PT',
            'AJUSTE_ENTRADA',
            'AJUSTE_SALIDA',
        ];

        return [
            'tipo'        => ['required', Rule::in($tipos)],
            'cantidad'    => ['required', 'integer', 'gt:0'],

            // Campos del formulario MP
            'id_bodega_mp'   => ['nullable', 'integer', 'exists:bodegas,id_bodega'],
            'id_articulo_mp' => ['nullable', 'integer', 'exists:articulos,id_articulo'],

            // Campos del formulario PT
            'id_bodega_pt'   => ['nullable', 'integer', 'exists:bodegas,id_bodega'],
            'id_articulo_pt' => ['nullable', 'integer', 'exists:articulos,id_articulo'],
            'id_pt_var'      => ['nullable', 'integer', 'exists:pt_variantes,id_pt_var'],

            'doc_ref'     => ['nullable', 'string', 'max:40'],
            'observacion' => ['nullable', 'string'],
            'fecha'       => ['nullable', 'date'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {

            $tipo = $this->input('tipo');
            $cantidad = (int) $this->input('cantidad');

            $idBodegaMp   = $this->input('id_bodega_mp');
            $idArticuloMp = $this->input('id_articulo_mp');

            $idBodegaPt   = $this->input('id_bodega_pt');
            $idArticuloPt = $this->input('id_articulo_pt');
            $idPtVar      = $this->input('id_pt_var');

            // TIPOS que trabajan con MP
            $esMP = in_array($tipo, ['COMPRA_MP', 'CONSUMO_MP'], true);

            // TIPOS que trabajan con PT
            $esPT = in_array($tipo, ['PRODUCCION_PT', 'VENTA_PT', 'AJUSTE_ENTRADA', 'AJUSTE_SALIDA'], true);

            // === Validación MP ===
            if ($esMP) {

                if (!$idBodegaMp) {
                    $v->errors()->add('id_bodega_mp', 'Debes seleccionar una bodega de MP.');
                }

                if (!$idArticuloMp) {
                    $v->errors()->add('id_articulo_mp', 'Debes seleccionar un artículo MP.');
                }
            }

            // === Validación PT ===
            if ($esPT) {

                if (!$idBodegaPt) {
                    $v->errors()->add('id_bodega_pt', 'Debes seleccionar una bodega PT.');
                }

                if (!$idArticuloPt) {
                    $v->errors()->add('id_articulo_pt', 'Debes seleccionar un artículo PT.');
                }

                // Validar variante si el artículo es PT
                if ($idArticuloPt) {
                    $art = DB::table('articulos')
                        ->select('tipo')
                        ->where('id_articulo', $idArticuloPt)
                        ->first();

                    if ($art && $art->tipo === 'PT') {

                        if (!$idPtVar) {
                            $v->errors()->add('id_pt_var', 'Debes seleccionar una variante para un PT.');
                        } else {

                            $ok = DB::table('pt_variantes')
                                ->where('id_pt_var', $idPtVar)
                                ->where('id_articulo_pt', $idArticuloPt)
                                ->exists();

                            if (!$ok) {
                                $v->errors()->add('id_pt_var', 'La variante no pertenece a ese artículo PT.');
                            }
                        }
                    }
                }
            }

            if ($cantidad <= 0) {
                $v->errors()->add('cantidad', 'La cantidad debe ser mayor que cero.');
            }
        });
    }
}
