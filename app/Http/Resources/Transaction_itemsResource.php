<?php

namespace App\Http\Resources;

use App\Models\Services;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction_itemsResource extends JsonResource
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
            "id" => $this->id,
            "transaction_id" => $this->transaction_id,
            "services" => Services::collection($this->service)
        ];
    }
}
