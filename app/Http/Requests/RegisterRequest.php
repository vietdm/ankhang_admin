<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
            'phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
            'fullname' => 'required',
            'password' => 'required',
            'present_phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
        ];
    }

    public function messages()
    {
        return [
            ...parent::messages(),
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.min' => 'Số điện thoại không đúng định dạng!',
            'present_phone.regex' => 'Số điện thoại người giới thiệu không đúng định dạng!',
            'present_phone.min' => 'Số điện thoại người giới thiệu không đúng định dạng!',
        ];
    }
}
