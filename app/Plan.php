<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $connection= 'oracle';
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_users',
        'max_projects',
        'description',
    ];

    public function arrDuration()
    {
        return [
            'Unlimited' => 'Unlimited',
            'Month' => 'Per Month',
            'Year' => 'Per Year',
        ];
    }
}
