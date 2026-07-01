<?php
namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'nullable|string|max:50',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:6',
            'location' => 'nullable|string|max:100',
        ];
    }
}
