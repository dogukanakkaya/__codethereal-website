<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use SoftDeletes;

    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sequence',
        'searchable',
        'created_by',
        'updated_by',
        'created_by_name'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'created_by_name'
    ];

    public function children()
    {
        return $this->hasMany(PostParent::class, 'parent_id');
    }

    /*
    public function parents()
    {
        return $this->belongsToMany(Content::class, 'content_parents', 'content_id', 'parent_id');
    }
    */
}
