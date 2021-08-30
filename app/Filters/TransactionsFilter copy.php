<?php

namespace App\Filters;

use App\Models\Transaction;
use App\Filters\Interfaces\Filterable;

class TransactionsFilter implements Filterable
{

  public function get()
  {

    return Transaction::query()
    ->when(request('transaction_id'), function ($query) {
      $query->where('id', request('transaction_id'));
    })
      ->when(request('customer'), function ($query) {
        $query->whereHas('customers', function ($query) {
          $query->where('name', 'LIKE', "%" . request('name') . "%");
        });
      })
      ->when(request('user'), function ($query) {
        $query->whereHas('users', function ($query) {
          $query->where('name', 'LIKE', "%" . request('user') . "%");
        });
      })
      ->when(request('value'), function ($query) {
        $query->where('value', 'LIKE', "%" . request('value') . "%");
      })
      ->orderBy(
        request('order_by', 'id'), // column
        request('direction', 'desc') // direction
      )
      ->paginate(request('size', ''));
  }
}
