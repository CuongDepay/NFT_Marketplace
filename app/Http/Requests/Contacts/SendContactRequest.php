<?php

namespace App\Http\Requests\Contacts;

use App\Http\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendContactRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => "required|phone_vietnam",
            'contact_category_id' => "required|exists:contact_categories,id",
            'title' => "required",
            "message" => "required"
        ];
    }

    public function messages(): array
    {
        return [
            "phone_vietnam" => "The :attribute field must be a valid phone number in Vietnam format."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failure($validator->errors(), 422));
    }
}
