<?php

namespace App\Http\Resources;

use App\Models\Transaction_items;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
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
            "user"=>new UsersResource($this->user),
            "customer"=>new CustomersResource($this->customer),
            "items"=>Transaction_items::collection($this->items),
        ];
    }
}
