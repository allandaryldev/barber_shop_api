<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItems extends Model
{
    use HasFactory;
    protected $fillable  = [
        // "transaction_id",
        "service_id",
        "quantity",
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
}
