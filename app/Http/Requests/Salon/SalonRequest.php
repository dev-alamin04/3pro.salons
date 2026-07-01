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
            'avatar'         => 'nullable|image|max:2048',
        ];
    }
}
