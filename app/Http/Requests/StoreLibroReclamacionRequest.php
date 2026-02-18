<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación del formulario público del Libro de Reclamaciones.
 * Campos obligatorios según INDECOPI.
 */
class StoreLibroReclamacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_documento' => ['required', 'string', 'in:DNI,CE,Pasaporte'],
            'numero_documento' => ['required', 'string', 'max:20', 'regex:/^[0-9A-Za-z\-]+$/'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:500'],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9\s\-\+]+$/'],
            'email' => ['required', 'email'],
            'tipo_reclamo' => ['required', 'string', 'in:reclamo,queja'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'pedido_consumidor' => ['nullable', 'string', 'max:2000'],
            'evento_id' => ['nullable', 'integer', 'exists:events,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'tipo_documento.in' => 'El tipo de documento debe ser DNI, CE o Pasaporte.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.regex' => 'El número de documento solo puede contener números y letras.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'tipo_reclamo.required' => 'Debe indicar si es reclamo o queja.',
            'tipo_reclamo.in' => 'El tipo debe ser reclamo o queja.',
            'descripcion.required' => 'El detalle del reclamo es obligatorio.',
        ];
    }
}
