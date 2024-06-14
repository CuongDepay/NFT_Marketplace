<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'items' => 'required|array',
            'items.*.nft_id' => 'required|exists:nfts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'code' => 'string|nullable',
            'company_name' => 'string|nullable',
            'shipping' => 'string|nullable',
            'tax' => 'string|nullable',
            'order_notes' => 'string|nullable'
        ];
    }
}
