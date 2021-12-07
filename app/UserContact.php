<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $connection= 'oracle';
    protected $fillable = [
        'parent_id',
        'user_id',
        'role',
    ];
}
