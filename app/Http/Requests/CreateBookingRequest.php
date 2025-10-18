<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'client_name' => ['required', 'string', 'min:2', 'max:255'],
            'client_phone' => ['required', 'string', 'regex:/^[+]?[0-9\s\-\(\)]+$/', 'min:10', 'max:20'],
            'start_time' => ['required', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Please select a service',
            'service_id.exists' => 'Selected service does not exist',
            'client_name.required' => 'Name is required',
            'client_name.min' => 'Name must be at least 2 characters',
            'client_phone.required' => 'Phone number is required',
            'client_phone.regex' => 'Please enter a valid phone number',
            'start_time.required' => 'Please select a time slot',
            'start_time.after' => 'Cannot book in the past',
        ];
    }
}
