<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ArticuloStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // agrega tu auth más adelante
    }

    public function rules(): array
    {
        return [
            'tipo'   => ['required', Rule::in(['MP','PT'])],
            'codigo' => ['required','string','max:50','unique:articulos,codigo'],
            'nombre' => ['required','string','max:150'],

            'id_unidad' => ['required','integer','exists:unidades,id_unidad'],
            'id_marca'  => ['nullable','integer','exists:marcas,id_marca'],

            // Campos condicionales
            'id_mp_tipo'         => ['nullable','integer','exists:mp_tipos,id_mp_tipo'],
            'id_categoria_pt'    => ['nullable','integer','exists:categorias_pt,id_categoria_pt'],
            'id_subcategoria_pt' => ['nullable','integer','exists:subcategorias_pt,id_subcategoria_pt'],

            'estado'  => ['required', Rule::in(['A','I'])],
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

            // Reglas por tipo
            if ($tipo === 'MP') {
                if (!$this->filled('id_mp_tipo')) {
                    $v->errors()->add('id_mp_tipo', 'Para artículos MP debes seleccionar el tipo de materia prima.');
                }
                // MP no requiere categoría/subcategoría PT
            }

            if ($tipo === 'PT') {
                if (!$this->filled('id_categoria_pt')) {
                    $v->errors()->add('id_categoria_pt', 'Para PT debes seleccionar la categoría.');
                }
                // subcategoría es opcional, pero si viene, debe pertenecer a la categoría
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
