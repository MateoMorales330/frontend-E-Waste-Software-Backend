<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
            'nombre' => ['sometimes', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'codigo_barras' => ['nullable', 'string', 'max:255'],
            'estado' => ['sometimes', 'in:disponible,agotado'],
            // AÑADIDO: permite cambiar la categoría por otra existente.
            'categoria_id' => ['sometimes', 'integer', 'exists:categorias,id'],
            // AÑADIDO: cambiar o quitar la imagen del producto.
            'imagen' => ['nullable', 'image', 'max:2048'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ];
    }
}
