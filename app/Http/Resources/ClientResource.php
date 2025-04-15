<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'nom'=> ucfirst($this->prenom).' '.strtoupper($this->nom),
            'email'=> $this->email,
            'telephone'=> $this->telephone,
            'user_id'=> $this->user_id,
        ];
    }
}
