<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            "customer_id"=>"required|exists:App\Models\Customers,id",
            'total' => 'required|numeric',
            'items.*' => 'required|array',
            'items.*.service_id' => 'required|numeric|exists:App\Models\Services,id',
            'items.*.quantity' => 'required|numeric',
            "useReward"=> 'required',
        ];
    }
}
