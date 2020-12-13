<?php

namespace App\Models\Admin\Menu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'menu_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Admin\Menu\Item');
    }

    /**
     * Find all items of menu group by locale
     *
     * @param $id
     * @return mixed
     */
    public static function itemsByLocale($id)
    {
        return self::find($id)
            ->items()
            ->oldest('sequence')
            ->latest()
            ->leftJoin('menu_item_translations', 'menu_item_translations.item_id', 'menu_items.id')
            ->where('language', app()->getLocale())
            ->get();
    }
}
