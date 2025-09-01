<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'          => 'required',
            'surname'       => 'required',
            'id_number'     => 'required|numeric|min:13',
            'mobile_number' => 'required|numeric',
            'email'         => 'required|email',
            'birth_date'    => 'required|date',
            'language_id'   => 'required|integer|exists:languages,id',
            'interests'     => 'required|array',
            'interests.*'   => 'required|integer|exists:interests,id',
        ];
    }
}
