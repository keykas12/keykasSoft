<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ArticuloUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // El parámetro de ruta es {articulo}; si usas binding por id_articulo:
        $id = $this->route('articulo');

        return [
            'tipo'   => ['required', Rule::in(['MP','PT'])],
            'codigo' => [
                'required','string','max:50',
                Rule::unique('articulos','codigo')->ignore($id, 'id_articulo'),
            ],
            'nombre' => ['required','string','max:150'],

            'id_unidad' => ['required','integer','exists:unidades,id_unidad'],
            'id_marca'  => ['nullable','integer','exists:marcas,id_marca'],

            'id_mp_tipo'         => ['nullable','integer','exists:mp_tipos,id_mp_tipo'],
            'id_categoria_pt'    => ['nullable','integer','exists:categorias_pt,id_categoria_pt'],
            'id_subcategoria_pt' => ['nullable','integer','exists:subcategorias_pt,id_subcategoria_pt'],

            'estado'  => ['required', Rule::in(['ACTIVO','INACTIVO'])],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'codigo' => $this->codigo ? trim($this->codigo) : null,
            'nombre' => $this->nombre ? trim($this->nombre) : null,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $tipo = $this->input('tipo');

            if ($tipo === 'MP') {
                if (!$this->filled('id_mp_tipo')) {
                    $v->errors()->add('id_mp_tipo', 'Para artículos MP debes seleccionar el tipo de materia prima.');
                }
            }

            if ($tipo === 'PT') {
                if (!$this->filled('id_categoria_pt')) {
                    $v->errors()->add('id_categoria_pt', 'Para PT debes seleccionar la categoría.');
                }
                if ($this->filled('id_categoria_pt') && $this->filled('id_subcategoria_pt')) {
                    $ok = DB::table('subcategorias_pt')
                        ->where('id_categoria_pt', $this->id_categoria_pt)
                        ->where('id_subcategoria_pt', $this->id_subcategoria_pt)
                        ->exists();

                    if (!$ok) {
                        $v->errors()->add('id_subcategoria_pt', 'La subcategoría no corresponde a la categoría seleccionada.');
                    }
                }
            }
        });
    }
}
