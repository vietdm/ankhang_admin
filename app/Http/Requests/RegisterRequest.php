<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
            'phone' => 'required',
            'fullname' => 'required',
            'password' => 'required',
        ];
    }
}
