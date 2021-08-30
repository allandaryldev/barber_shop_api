<?php

namespace App\Filters;

use App\Models\Services;
use App\Filters\Interfaces\Filterable;

class ServiceFilter implements Filterable
{

  public function get()
  {

    return Services::query()
      ->when(request('id'), function ($query) {
        $query->where('id', request('id'));
      })
      ->when(request('name'), function ($query) {
        $query->where('name', 'LIKE', "%" . request('name') . "%");
      })
      ->when(request('value'), function ($query) {
        $query->where('value', 'LIKE', "%" . request('value') . "%");
      })
      ->orderBy(
        request('order_by', 'id'), // column
        request('direction', 'desc') // direction
      )
      ->paginate(request('rows', 10));
  }
}
