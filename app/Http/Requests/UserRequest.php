<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users,username,' . request('id'),
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'sometimes|nullable|email|unique:users,email,' . request('id'),
            'type' => 'required|in:admin,staff',
            'status' => 'required|in:active,deativated',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ];
    }
}
