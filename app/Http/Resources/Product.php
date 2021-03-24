<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'name' => $this->name,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'vat' => $this->category->vat,
            'weight' => $this->weight,
            'unit' => $this->unit->name,
            'unit_id' => $this->unit_id,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
            'quantity' => $this->stock->quantity,
            'deleted_at' => $this->deleted_at
        ];
    }
}