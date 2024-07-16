<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestStudent extends ApiFormRequest
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
            'username' => 'required|string|max:100',
            'password' => 'required',
            'full_name' => 'required',
            'student_code' => 'required',
            'grade' => 'required',
            'birthday'=> 'required',
            'gender'=> 'required',
            'address'=> 'required',
            'school'=> 'required',
            'district'=> 'required',
            'city'=> 'required',
            'parent_phone'=> 'required',


        ];
    }
}
