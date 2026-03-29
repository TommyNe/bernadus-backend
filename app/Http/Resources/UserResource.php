<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'email_verified_at' => $this->serializeDate($this->email_verified_at),
            'two_factor_confirmed_at' => $this->serializeDate($this->two_factor_confirmed_at),
            ...$this->timestamps($request),
        ];
    }
}
