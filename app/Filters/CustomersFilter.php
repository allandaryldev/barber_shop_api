<?php

namespace App\Filters;

use App\Models\Customers;
use App\Filters\Interfaces\Filterable;

class CustomersFilter implements Filterable
{

    public function get()
    {

        return Customers::query()
            ->when(request('customer'), function ($query) {
                $query->where('name', 'LIKE', "%" . request('customer') . "%");
            })
            ->orderBy(
                request('order_by', 'id'), // column
                request('direction', 'desc') // direction
              )
            ->paginate(request('size', 10));
    }
}
