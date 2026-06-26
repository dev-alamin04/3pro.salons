<?php
namespace App\Http\Requests\Salon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:100',
            'location'       => 'nullable|string',
            'lat'            => 'nullable|string',
            'lang'           => 'nullable|string',
            'address'        => 'nullable|string',
            'start_sequence' => 'nullable|integer|min:1',
            'salon_id'       => [
                'nullable', 'string',
                Rule::unique('salons', 'salon_id')->ignore($this->salon?->id),
                Rule::unique('users', 'secret_key'),
            ],
            'avatar'         => 'nullable|image|max:2048',
        ];
    }
}
