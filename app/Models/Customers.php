<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mobile_no',
        'birth_date',
        'email',
        'notes  ',
        'password',
        'points',
        'group',
    ];
}
