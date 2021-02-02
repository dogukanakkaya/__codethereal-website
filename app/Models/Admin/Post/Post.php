<?php

namespace App\Models\Admin\Post;

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

    public function childrens()
    {
        return $this->hasMany(PostParent::class, 'parent_id');
    }

    /*
    public function parents()
    {
        return $this->belongsToMany(Content::class, 'content_parents', 'content_id', 'parent_id');
    }
    */

    public static function findByLocaleInstance(...$select)
    {
        return self::select($select)->where('language', app()->getLocale())->leftJoin('post_translations', 'post_translations.post_id', 'posts.id');
    }

    /**
     * Find all records with active locale
     *
     * @param mixed ...$select
     * @return mixed
     */
    public static function findAllByLocale(...$select): mixed
    {
        return self::findByLocaleInstance(...$select)->latest()->get();
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
        return self::findByLocaleInstance(...$select)->where('posts.id', $id)->first();
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
        return self::findByLocaleInstance(...$select)->where('url', $url)->first();
    }

    /**
     * Return post parents
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findParentsByLocale(int $id, ...$select)
    {
        return self::findByLocaleInstance(...$select)
            ->where('post_parents.post_id', $id)
            ->leftJoin('post_parents', 'post_parents.parent_id', 'posts.id')
            ->get();
    }

    /**
     * Return post relations
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findRelationsByLocale(int $id, ...$select)
    {
        return self::findByLocaleInstance(...$select)
            ->where('post_relations.post_id', $id)
            ->leftJoin('post_relations', 'post_relations.relation_id', 'posts.id')
            ->get();
    }

    /**
     * Return post files
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public static function findFiles(int $id, ...$select)
    {
        return self::select($select)
            ->where('post_files.post_id', $id)
            ->leftJoin('post_files', 'post_files.post_id', 'posts.id')
            ->leftJoin('files', 'files.id', 'post_files.file_id')
            ->get();
    }

    /**
     * Return first file post has
     *
     * @param int $id
     * @return mixed
     */
    public static function findFile(int $id): mixed
    {
        return self::select('path')
            ->where('posts.id', $id)
            // TODO: check this out, it's not working, it always returns the type 1
            ->where(function ($query) {
                $query->where('files.type', 2)->orWhere('files.type', 1);
            })
            ->leftJoin('post_files', 'post_files.post_id', '=', 'posts.id')
            ->leftJoin('files', 'files.id', 'post_files.file_id')
            ->first();
    }

    /**
     * Return query instance to find sub posts of given post id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubPostsByLocaleInstance(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        $query = self::findByLocaleInstance(...$select)
            ->leftJoin('post_parents', 'post_parents.post_id', 'posts.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit);
        if (is_array($id)){
            return $query->whereIn('post_parents.parent_id', $id);
        }
        return $query->where('post_parents.parent_id', $id);
    }

    /**
     * Find sub posts of given post id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubPostsByLocale(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::findSubPostsByLocaleInstance($id, $select, $limit)->get();
    }

    /**
     * Find sub posts of given post id|ids with children's count
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findSubPostsWithChildrenCountByLocale(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::findSubPostsByLocaleInstance($id, $select, $limit)->withCount('childrens')->get();
    }

    /**
     * Return relational posts of given post id
     *
     * @param int $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findRelationalPostsByLocale(int $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return self::findByLocaleInstance(...$select)
            ->leftJoin('post_relations', 'post_relations.relation_id', 'posts.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit)
            ->where('post_relations.post_id', $id)
            ->get();
    }

    /**
     * Return most viewed posts under given post id
     *
     * @param int|array $id
     * @param array|string[] $select
     * @param int|null $limit
     * @return mixed
     */
    public static function findMostViewedSubPosts(int|array $id, array$select = ['*'], int|null $limit = null): mixed
    {
        $query = self::findByLocaleInstance(...$select)
            ->leftJoin('post_parents', 'post_parents.post_id', 'posts.id')
            ->latest('views')
            ->take($limit);
        if (is_array($id)){
            return $query->whereIn('post_parents.parent_id', $id)->get();
        }
        return $query->where('post_parents.parent_id', $id)->get();
    }

    /**
     * Return if given post id has sub posts or not
     *
     * @param int $id
     * @return bool
     */
    public static function hasSubPosts(int $id)
    {
        return DB::table('post_parents')->where('parent_id', $id)->exists();
    }

    /**
     * Return the given post id's parentTree (for breadcrumb)
     *
     * @param int $id
     * @param string[] $select
     * @return array
     */
    public static function parentTree(int $id, $select = ['*'])
    {
        $tree = [];
        while($post = self::findByLocaleInstance(...$select)
            ->where('post_parents.post_id', $id)
            ->leftJoin('post_parents', 'post_parents.parent_id', 'posts.id')
            ->first()){
            $tree[$id] = [
                'title' => $post->title,
                'url' => $post->url
            ];
            $id = $post->id;
        }
        return array_reverse($tree);
    }
}
