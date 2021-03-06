<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    protected $connection= 'oracle';
    protected $fillable = [
        'project_id',
        'user_id',
        'permission',
        'user_permission',
        'invited_by',
    ];
}
