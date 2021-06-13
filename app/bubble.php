<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bubble extends Model
{
    protected $fillable = [
        'label', 'data', 'borderWidth', 'backgroundColor', 'pointHitRadius', 'animations'
    ];
}
