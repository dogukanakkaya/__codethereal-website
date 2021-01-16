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

    public function children()
    {
        return $this->hasMany('App\Models\Admin\Content', 'parent_id');
    }

    /**
     * Find all records with active locale
     *
     * @param mixed ...$select
     * @return mixed
     */
    public static function findAllByLocale(...$select): mixed
    {
        return self::select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->latest()
            ->get();
    }

    /**
     * Find one record with active locale
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findOneByLocale(int $id, ...$select): mixed
    {
        return self::select($select)
            ->where('contents.id', $id)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->first();
    }

    /**
     * Return content parents
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findParents(int $id, ...$select)
    {
        return self::select($select)
            ->where('content_parents.content_id', $id)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_parents', 'content_parents.parent_id', 'contents.id')
            ->get();
    }

    /**
     * Return content files
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findFiles(int $id, ...$select)
    {
        return self::select($select)
            ->where('content_files.content_id', $id)
            ->leftJoin('content_files', 'content_files.content_id', 'contents.id')
            ->leftJoin('files', 'files.id', 'content_files.file_id')
            ->get();
    }

    /**
     * Return first file content has
     *
     * @param int $id
     * @return mixed
     */
    public static function findFile(int $id): mixed
    {
        return self::select('path')
            ->where('contents.id', $id)
            // TODO: check this out, it's not working, it always returns the type 1
            ->where(function ($query) {
                $query->where('files.type', 2)->orWhere('files.type', 1);
            })
            ->leftJoin('content_files', 'content_files.content_id', '=', 'contents.id')
            ->leftJoin('files', 'files.id', 'content_files.file_id')
            ->first();
    }

    /**
     * Return query instance to find sub contents of given content id
     *
     * @param int $parentId
     * @param mixed ...$select
     * @return mixed
     */
    private static function findSubContentsByLocaleInstance(int $parentId, ...$select): mixed
    {
        return self::select($select)
            ->where('parent_id', $parentId)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->oldest('sequence')
            ->latest();
    }

    /**
     * Find sub contents of given content id
     *
     * @param int $parentId
     * @param mixed ...$select
     * @return mixed
     */
    public static function findSubContentsByLocale(int $parentId, ...$select): mixed
    {
        return self::findSubContentsByLocaleInstance($parentId, ...$select)->get();
    }

    /**
     * Find sub contents of given content id with children's count
     *
     * @param int $parentId
     * @param mixed ...$select
     * @return mixed
     */
    public static function findSubContentsWithChildrenCountByLocale(int $parentId, ...$select): mixed
    {
        return self::findSubContentsByLocaleInstance($parentId, ...$select)->withCount('children')->get();
    }
}
