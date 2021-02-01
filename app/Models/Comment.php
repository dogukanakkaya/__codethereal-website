<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment',
        'parent_id',
        'content_id',
        'user_id',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $dates = ['created_at'];

    protected $appends = ['createdAtHumanReadable'];

    public function getCreatedAtHumanReadableAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
