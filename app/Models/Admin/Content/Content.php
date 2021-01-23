<?php

namespace App\Models\Admin\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function childrens()
    {
        return $this->hasMany(ContentParent::class, 'parent_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Content::class, 'content_parents', 'content_id', 'parent_id');
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
     * Find one record by active locale
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
     * Find one record by active locale with link
     *
     * @param string $url
     * @param mixed ...$select
     * @return mixed
     */
    public static function findOneByLocaleWithUrl(string $url, ...$select): mixed
    {
        return self::select($select)
            ->where('url', $url)
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
    public static function findParentsByLocale(int $id, ...$select)
    {
        // TODO: do this with parents relation
        return self::select($select)
            ->where('content_parents.content_id', $id)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_parents', 'content_parents.parent_id', 'contents.id')
            ->get();
    }

    /**
     * Return content relations
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findRelationsByLocale(int $id, ...$select)
    {
        // TODO: do this with relations relation
        return self::select($select)
            ->where('content_relations.content_id', $id)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_relations', 'content_relations.relation_id', 'contents.id')
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
     * Return query instance to find sub contents of given content id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubContentsByLocaleInstance(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        $query = self::select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_parents', 'content_parents.content_id', 'contents.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit);
        if (is_array($id)){
            return $query->whereIn('content_parents.parent_id', $id);
        }
        return $query->where('content_parents.parent_id', $id);
    }

    /**
     * Find sub contents of given content id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubContentsByLocale(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::findSubContentsByLocaleInstance($id, $select, $limit)->get();
    }

    /**
     * Find sub contents of given content id|ids with children's count
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubContentsWithChildrenCountByLocale(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::findSubContentsByLocaleInstance($id, $select, $limit)->withCount('childrens')->get();
    }

    /**
     * Return relational contents of given content id
     *
     * @param int $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findRelationalContentsByLocale(int $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_relations', 'content_relations.relation_id', 'contents.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit)
            ->where('content_relations.content_id', $id)
            ->get();
    }

    public static function findMostViewedSubContents(int|array $id, array$select = ['*'], int|null $limit = null): mixed
    {
        $query = self::select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->leftJoin('content_parents', 'content_parents.content_id', 'contents.id')
            ->latest('views')
            ->take($limit);
        if (is_array($id)){
            return $query->whereIn('content_parents.parent_id', $id)->get();
        }
        return $query->where('content_parents.parent_id', $id)->get();
    }

    /**
     * Return if given content id has sub contents or not
     *
     * @param int $id
     * @return bool
     */
    public static function hasSubContents(int $id)
    {
        return DB::table('content_parents')->where('parent_id', $id)->count() > 0;
    }

    public static function parentTree(int $id, $select = ['*'])
    {
        $tree = [];
        while($content = self::find($id)
            ->parents()
            ->select($select)
            ->where('language', app()->getLocale())
            ->leftJoin('content_translations', 'content_translations.content_id', 'contents.id')
            ->first()){
            $tree[$id] = [
                'title' => $content->title,
                'url' => $content->url
            ];
            $id = $content->id;
        }
        return array_reverse($tree);
    }
}
