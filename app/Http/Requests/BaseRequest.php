<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        // if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException(response()->json($validator->errors(), 422));
        // }
        // parent::failedValidation($validator);
    }
}
