<?php

namespace App\Http\Resources\item;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractorItemDeletedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'real_article' => $this->real_article,
            'name' => $this->name,
            'relation_name' => $this->relation_name,
            'price' => $this->price,
        ];
    }
}
