<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'avatar_path'      => $this->avatar_path,
            'secret_key'       => $this->secret_key,
            'specialist'       => $this->specialist,
            'disc_tag'         => $this->disc_tag,
            'experience_level' => $this->experience_level,
            'pronoun'          => $this->pronoun,
            'salon_name'       => $this->currentSalon?->salon?->name,
            'salon_location'   => $this->currentSalon?->salon?->location,
            'badge'            => $this->badge,
            'tier_level'       => $this->tier_level,
        ];
    }
}
