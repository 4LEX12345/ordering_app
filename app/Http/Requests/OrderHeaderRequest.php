<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderHeaderRequest extends FormRequest
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
            'payment_date' => 'required',
            'payment_method' => 'required',
            'amount' => 'required|numeric|integer|min:1',
            'proof_of_payment_name' => 'required',
        ];
    }
}
