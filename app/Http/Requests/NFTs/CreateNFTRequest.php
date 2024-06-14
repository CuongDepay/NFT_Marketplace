<?php

namespace App\Http\Requests\NFTs;

use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateNFTRequest extends FormRequest
{
    use RespondsWithHttpStatus;

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
            'title' => 'required',
            'price' => 'required',
            'starting_date' => 'required',
            'expiration_date' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'image_url' => 'required|mimes:jpg,png,jpeg',
            'collection_id' => 'required',
        ];
    }

    /**
     * Get custom validation messages (optional).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'price.required' => 'The price field is required.',
            'starting_date.required' => 'The starting date field is required.',
            'expiration_date.required' => 'The expiration date field is required.',
            'description.required' => 'The description field is required.',
            'quantity.required' => 'The quantity field is required.',
            'image_url.required' => 'The image field is required.',
            'image_url.mimes' => 'The image must be a file of type: jpg, png, jpeg.',
            'collection_id' => 'The collection id is required.'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure($validator->errors(), 422));
    }
}
