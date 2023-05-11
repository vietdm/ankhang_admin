<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'regex:/^[a-zA-Z][a-zA-Z0-9]{3,31}$/'],
            'email' => ['required', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/'],
            'phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
            'fullname' => 'required',
            'password' => 'required',
            'present_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Username phải có ít nhất 4 ký tự, viết liền, chỉ bao gồm chữ thường, chữ hoa và số!',
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.min' => 'Số điện thoại không đúng định dạng!',
            'email.regex' => 'Email không hợp lệ!',
        ];
    }
}
