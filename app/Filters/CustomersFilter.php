<?php

namespace App\Filters;

use App\Models\Customers;
use App\Filters\Interfaces\Filterable;

class CustomersFilter implements Filterable
{

    public function get()
    {

        return Customers::query()
        ->when(request('id'), function ($query) {
            $query->where('id', request('id'));
          })
            ->when(request('name'), function ($query) {
                $query->where('name', 'LIKE', "%" . request('name') . "%");
            })
            ->when(request('group'), function ($query) {
                $query->where('group', request('group') );
            })
            ->when(request('mobile_no'), function ($query) {
                $query->where('mobile_no', 'LIKE', "%" . request('mobile_no') . "%");
            })
            ->when(request('points'), function ($query) {
                $query->where('points', request('points') );
            })
            ->orderBy(
                request('order_by', 'id'), // column
                request('direction', 'desc') // direction
              )
            ->paginate(request('size', 10));
    }
}
