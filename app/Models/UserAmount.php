<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAmount extends Model
{
    protected $table = 'users_amount';

    protected $fillable = [
        'amount',
        'user_id',
    ];

}
