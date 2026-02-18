<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOrganizer() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:draft,pending_approval'],
            'ticket_price' => ['nullable', 'numeric', 'min:0'],
            'available_tickets' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título del evento es obligatorio.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'description.required' => 'La descripción es obligatoria.',
            'venue_name.required' => 'El nombre del lugar es obligatorio.',
            'venue_address.required' => 'La dirección es obligatoria.',
            'city.required' => 'La ciudad es obligatoria.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
        ];
    }
}
