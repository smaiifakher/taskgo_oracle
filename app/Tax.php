<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $connection= 'oracle';
    protected $fillable = [
        'name',
        'rate',
        'created_by',
    ];
}
