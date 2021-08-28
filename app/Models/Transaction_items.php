<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_items extends Model
{
    use HasFactory;
    protected $fillable  = [
        "transaction_id",
        "service_id",
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }
    public function service()
    {
        return $this->belongsTo(Services::class);
    }
}
