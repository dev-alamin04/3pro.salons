<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $userId = auth()->id(); // current logged-in user

        return [
            'name'        => 'nullable|string|max:50',
            'avatar_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480', // 20MB
            'location'    => 'nullable|string|max:255',
            'pronoun'     => 'nullable|string|max:50',
            'specialist'  => 'nullable|string|max:100',
            'disc_tag'    => 'nullable|string|max:50',
            'badges_alert' => 'nullable|boolean',
            'goals_alert'  => 'nullable|boolean',
            'timezone'     => 'nullable|string'
        ];
    }
}
