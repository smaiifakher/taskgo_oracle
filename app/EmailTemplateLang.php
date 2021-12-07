<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateLang extends Model
{
    protected $connection = 'oracle';

    protected $fillable = [
        'parent_id',
        'lang',
        'subject',
        'content',
    ];
}
