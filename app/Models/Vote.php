<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'votes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vote',
        'post_id',
        'user_id'
    ];

    protected $hidden = [
        'updated_at'
    ];
}
