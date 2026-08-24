<?php

namespace App\Http\Requests\Contact;

use App\Http\Requests\BaseRequest;

class StoreContactRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'job_title'   => 'nullable|string|max:255',
            'salon_name'  => 'nullable|string|max:255',
            'city_state'  => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'required|email|max:255',
            'message'     => 'required|string',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Your name is required.',
            'email.required'   => 'Your email address is required.',
            'email.email'      => 'Please enter a valid email address.',
            'message.required' => 'Please tell us what is going on.',
            'attachment.mimes' => 'Only JPG, PNG, MP4, MOV, or AVI files are allowed.',
            'attachment.max'   => 'The attachment must not exceed 50 MB.',
        ];
    }
}
