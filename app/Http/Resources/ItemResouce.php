<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controls = sprintf("<a href='#' onclick='window.setRelation(\"%s\", this);'><i class='fa fa-lg fa-link m-1'></i></a>",
            $this->article
        );

        return [
            'id' => $this->id,
            'article' => $this->article,
            'brand_name' => $this->brand_name,
            'item_name' => $this->item_name,
            'price' => $this->price,
            'func' => $controls,
        ];
    }
}
