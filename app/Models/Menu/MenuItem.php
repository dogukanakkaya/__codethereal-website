<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes;

    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'menu_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'parent_id',
        'sequence'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
        'created_at'
    ];
}
