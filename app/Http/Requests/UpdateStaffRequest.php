<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'photo' => 'nullable|image',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'contact_no' => 'required|string|max:15',
            'second_contact_no' => 'nullable|string|max:15',
            'birthday' => 'nullable|date',
            'nic_no' => 'required|string|max:20',
            'address' => 'nullable|string',
            'basic' => 'required|numeric',
            'fixed_allowance' => 'nullable|numeric',
            'working_days_and_hours' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:staff,email,' . $this->route('staff'),
            'password' => 'nullable|string|min:8',
        ];
    }
}
