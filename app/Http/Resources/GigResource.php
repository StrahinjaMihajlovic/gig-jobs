<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GigResource extends JsonResource
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
            'number_of_positions' => $this->number_of_positions,
            'pay_per_hour' => $this->pay_per_hour,
            'status' => $this->status ? 'posted' : 'draft',
            'time_started' => $this->timestamp_start,
            'time_ending' => $this->timestamp_end,
            'company' => $this->company->name
        ];
    }
}
