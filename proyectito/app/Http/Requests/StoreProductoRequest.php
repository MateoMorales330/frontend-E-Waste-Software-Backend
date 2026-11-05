<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'codigo_barras' => ['nullable', 'string', 'max:255'],
            'estado' => ['sometimes', 'in:disponible,agotado'],
            // AÑADIDO: obliga a seleccionar una categoría existente.
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
