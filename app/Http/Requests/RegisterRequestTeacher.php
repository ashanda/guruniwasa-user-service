<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestTeacher extends ApiFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|unique:teachers',
            'name' => 'required',
            'grades' => 'required',
            'address' => 'required',
            'district' => 'required',
            'town' => 'required',
            'contact_no' => 'required',
            'secondary_contact_no' => 'nullable',
            'email' => 'required|email|unique:teachers',
            'password' => 'required|min:8',
        ];
    }
}
