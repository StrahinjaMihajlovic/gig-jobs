<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'created' => $this->created_at,
            'number_of_posted_gigs' => $this->gigs()->ByStatus(1)->paginate(),
            'number_of_started_gigs' => $this->gigs()->ByProgress('started')->paginated()
        ];
    }
}
