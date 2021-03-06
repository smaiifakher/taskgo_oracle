<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $connection= 'oracle';

    protected $fillable = [
        'name',
        'from',
        'keyword',
        'created_by',
    ];

    public function template($project_id)
    {
        return $this->hasOne('App\ProjectEmailTemplate', 'template_id', 'id')->where('project_id', '=', $project_id)->first();
    }
}
