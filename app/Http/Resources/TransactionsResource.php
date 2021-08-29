<?php

namespace App\Http\Resources;

use App\Models\Transaction_items;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
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
            "id"=>$this->id,
            "user"=>new UsersResource($this->user),
            "customer"=>new CustomersResource($this->customer),
            "items"=>Transaction_itemsResource::collection($this->items),
            // "total"=>number_format($this->total,2),
            "total"=>$this->total,
            "created_at"=>Carbon::parse($this->created_at)->format('M d, Y '),
        ];
    }
}
