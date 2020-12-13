<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes;

    /**
     * @var string
     *
     * Table name of model
     */
    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'sequence',
        'searchable'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    /**
     * Find all records with active locale
     *
     * @param mixed ...$select
     * @return mixed
     */
    public static function findAllByLocale(...$select)
    {
        return self::select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->latest();
    }

    /**
     * Find one record with active locale
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findOneByLocale(int $id, ...$select)
    {
        return self::select($select)
            ->where('contents.id', $id)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->first();
    }
}
