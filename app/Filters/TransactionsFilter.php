<?php

namespace App\Filters;

use App\Models\Transaction;
use App\Filters\Interfaces\Filterable;

class TransactionsFilter implements Filterable
{

  public function get()
  {

    return Transaction::query()
    ->when(request('id'), function ($query) {
      $query->where('id', request('id'));
    })
      ->when(request('name'), function ($query) {
        $query->whereHas('customer', function ($query) {
          $query->where('name', 'LIKE', "%" . request('name') . "%");
        });
      })
      ->when(request('user'), function ($query) {
        $query->whereHas('users', function ($query) {
          $query->where('name', 'LIKE', "%" . request('user') . "%");
        });
      })
      ->when(request('total'), function ($query) {
        $query->where('total', 'LIKE', "%" . request('total') . "%");
      })
      ->orderBy(
        request('order_by', 'id'), // column
        request('direction', 'desc') // direction
      )
      ->paginate(request('rows', ''));
  }
}
