<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable  = [
        "user_id",
        "customer_id",
        "total"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    public function items()
    {
        return $this->hasMany(TransactionItems::class);
    }
}
