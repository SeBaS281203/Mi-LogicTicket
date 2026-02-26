<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->session()->flash('auth_modal', 'register');
        parent::failedValidation($validator);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->input('email'))),
            'first_name' => trim((string) $this->input('first_name')),
            'last_name' => trim((string) $this->input('last_name')),
            'country' => trim((string) $this->input('country')),
            'city' => trim((string) $this->input('city')),
            'document_number' => strtoupper(trim((string) $this->input('document_number'))),
            'phone' => trim((string) $this->input('phone')),
            'organization_name' => trim((string) $this->input('organization_name')),
            'organization_address' => trim((string) $this->input('organization_address')),
            'ruc' => preg_replace('/\D+/', '', (string) $this->input('ruc')),
            'marketing_consent' => $this->boolean('marketing_consent'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:120'],
            'last_name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'country' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:120'],
            'document_type' => ['required', 'in:dni,ce,pasaporte'],
            'document_number' => ['required', 'string', 'min:6', 'max:20', 'regex:/^[A-Z0-9-]+$/'],
            'gender' => ['required', 'in:male,female,other,prefer_not'],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'regex:/^[0-9+\s()\-]+$/'],
            'role' => ['required', 'in:client,organizer'],
            'organization_name' => ['required_if:role,organizer', 'nullable', 'string', 'min:2', 'max:255'],
            'organization_address' => ['required_if:role,organizer', 'nullable', 'string', 'min:5', 'max:255'],
            'ruc' => ['required_if:role,organizer', 'nullable', 'digits:11', 'unique:users,ruc'],
            'marketing_consent' => ['nullable', 'boolean'],
            'terms_accepted' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'last_name.required' => 'Los apellidos son obligatorios.',
            'last_name.min' => 'Los apellidos deben tener al menos 2 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'country.required' => 'Debes seleccionar un país.',
            'city.required' => 'Debes ingresar tu ciudad.',
            'document_type.required' => 'Selecciona el tipo de documento.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.min' => 'El número de documento es demasiado corto.',
            'document_number.regex' => 'El número de documento solo puede tener letras, números y guion.',
            'gender.required' => 'Selecciona un género.',
            'gender.in' => 'El género seleccionado no es válido.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.regex' => 'El formato del teléfono no es válido.',
            'role.required' => 'Debes elegir un tipo de cuenta.',
            'role.in' => 'El tipo de cuenta seleccionado no es válido.',
            'organization_name.required_if' => 'Para organizadores, el nombre de la organización es obligatorio.',
            'organization_address.required_if' => 'Para organizadores, la dirección fiscal es obligatoria.',
            'ruc.required_if' => 'Para organizadores, el RUC es obligatorio.',
            'ruc.digits' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este RUC ya está registrado.',
            'terms_accepted.accepted' => 'Debes aceptar los términos y condiciones para continuar.',
        ];
    }
}
