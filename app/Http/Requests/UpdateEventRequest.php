<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');
        return $event && $this->user() && (
            $this->user()->id === $event->user_id || $this->user()->isAdmin()
        );
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
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:draft,pending_approval,cancelled'],
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
        ];
    }
}
