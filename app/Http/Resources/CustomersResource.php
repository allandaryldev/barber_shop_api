<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CustomersResource extends JsonResource
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
            "name" => $this->name,
            "mobile_no" => $this->mobile_no,
            "email" => $this->email,
            "birth_date" => $this->birth_date ? $this->birth_date:null,
            "birth_date_formatted" => $this->birth_date ? Carbon::parse($this->birth_date)->format('M d, Y '):"",
            "notes" => $this->notes,
            "points" => $this->points,
            "group" => $this->group,

        ];
    }
}
