<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidPhNumber;

class CustomerRequest extends FormRequest
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
            'name'=>'required',
            // 'email' => 'sometimes|nullable|email|unique:customers,email,' . request('id'),
            'mobile_no' => [
                'required', 'unique:customers,mobile_no,' . request('id'),
                new ValidPhNumber
            ],
            'birth_date' => 'sometimes|nullable|date',
            'notes ' =>'sometimes|nullable',
            'group' => 'required|in:vip,regular',
        ];
    }
}
