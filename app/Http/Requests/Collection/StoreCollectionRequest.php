<?php

namespace App\Http\Requests\Collection;

use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCollectionRequest extends FormRequest
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
            "url" => "required",
            "name" => "required",
            "description" => "required",
            "starting_date" => "required",
            "expiration_date" => "required",
            "price" => "required|numeric",
            "logo_image_url" => "required|mimes:jpg,png,jpeg",
            "featured_image_url" => "required|mimes:jpg,png,jpeg",
            "cover_image_url" => "required|mimes:jpg,png,jpeg",
            "category_id" => "required"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure($validator->errors(), 422));
    }
}
