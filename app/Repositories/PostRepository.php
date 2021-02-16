<?php

namespace App\Repositories;

use App\Models\Post\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface
{
    /*** ONLY ADMIN ***/
    /**
     * Insert parents
     *
     * @param int $id
     * @param array $parentIds
     * @return bool
     */
    public function insertParents(int $id, array $parentIds): bool
    {
        $data = [];
        foreach ($parentIds as $parentId) {
            if (empty($parentId)) continue;
            $data[] = [
                'post_id' => $id,
                'parent_id' => $parentId
            ];
        }

        return DB::table('post_parents')->insert($data);
    }

    /**
     * Insert relations
     *
     * @param int $id
     * @param array $relationIds
     * @return bool
     */
    public function insertRelations(int $id, array $relationIds): bool
    {
        $data = [];
        foreach ($relationIds as $relationId) {
            if (empty($relationId)) continue;
            $data[] = [
                'post_id' => $id,
                'relation_id' => $relationId
            ];
        }

        return DB::table('post_relations')->insert($data);
    }

    /**
     * Insert files
     *
     * @param int $id
     * @param array $fileIds
     * @return bool
     */
    public function insertFiles(int $id, array $fileIds): bool
    {
        $data = [];
        foreach ($fileIds as $fileId) {
            if (empty($fileId)) continue;
            $data[] = [
                'post_id' => $id,
                'file_id' => $fileId
            ];
        }

        return DB::table('post_files')->insert($data);
    }

    /**
     * Delete and update parents
     *
     * @param int $id
     * @param array $parentIds
     * @return bool
     */
    public function updatePostParents(int $id, array $parentIds): bool
    {
        $delete = DB::table('post_parents')->where('post_id', $id)->delete();
        $insert = $this->insertParents($id, $parentIds);
        return $delete && $insert;
    }

    /**
     * Delete and update relations
     *
     * @param int $id
     * @param array $relationIds
     * @return bool
     */
    public function updatePostRelations(int $id, array $relationIds): bool
    {
        $delete = DB::table('post_relations')->where('post_id', $id)->delete();
        $insert = $this->insertRelations($id, $relationIds);
        return $delete && $insert;
    }

    /**
     * Delete and update files
     *
     * @param int $id
     * @param array $fileIds
     * @return bool
     */
    public function updatePostFiles(int $id, array $fileIds): bool
    {
        $delete = DB::table('post_files')->where('post_id', $id)->delete();
        $insert = $this->insertFiles($id, $fileIds);
        return $delete && $insert;
    }

    /**
     * Insert translations
     *
     * @param int $id
     * @param $data
     * @return bool
     */
    public function insertTranslations(int $id, $data): bool
    {
        // Find Post featured image and wide image to save database
        $featuredImage = $this->firstFile($id);

        $translationData = [];
        foreach ($data as $language => $values) {
            // If same named record exists add -id suffix to the url
            $recordExists = DB::table('posts')
                ->where('language', $language)
                ->where('title', $values['title'])
                ->whereNull('deleted_at')
                ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
                ->exists();
            $appendId = ($recordExists) ? '-'.$id : '';

            $translationData[] = array_merge($values, [
                'language' => $language,
                'post_id' => $id,
                'url' => Str::slug($values['title']) . $appendId,
                'categories' => implode(', ', $this->parents($id, ['title'])->pluck('title')->toArray()),
                'featured_image' => $featuredImage->path ?? ''
            ]);
        }
        return DB::table('post_translations')->insert($translationData);
    }

    /**
     * Update translations
     *
     * @param int $id
     * @param $data
     */
    public function updateTranslations(int $id, $data): void
    {
        // Find Post featured image and wide image to save database
        $featuredImage = $this->firstFile($id);

        foreach ($data as $language => $values) {
            // If same named record exists add -id suffix to the url
            $recordExists = DB::table('posts')
                ->where('posts.id', '!=', $id)
                ->where('language', $language)
                ->where('title', $values['title'])
                ->whereNull('deleted_at')
                ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
                ->exists();

            $appendId = ($recordExists) ? '-'.$id : '';
            $values['url'] = Str::slug($values['title']) . $appendId;

            $values['featured_image'] = $featuredImage->path ?? '';

            $values['categories'] = implode(', ', $this->parents($id, ['title'])->pluck('title')->toArray());

            DB::table('post_translations')
                ->where('post_id', $id)
                ->where('language', $language)
                ->update($values);
        }
    }

    /**
     * Return the selectables that only contains title and id
     *
     * @return mixed
     */
    public function selectables(): mixed
    {
        return $this->localeInstance('posts.id', 'title')->pluck('title', 'id')->toArray();
    }
    /*** ONLY ADMIN ***/

    /**
     * Return parents
     *
     * @param int $id
     * @param mixed ...$select
     * @param int|null $limit
     * @return mixed
     */
    public function parents(int $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return $this->localeInstance(...$select)
            ->where('post_parents.post_id', $id)
            ->leftJoin('post_parents', 'post_parents.parent_id', 'posts.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Return relations
     *
     * @param int $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public function relations(int $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return $this->localeInstance(...$select)
            ->where('post_relations.post_id', $id)
            ->leftJoin('post_relations', 'post_relations.relation_id', 'posts.id')
            ->oldest('sequence')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Return files
     *
     * @param int $id
     * @param mixed ...$select
     * @return mixed
     */
    public function files(int $id, array $select = ['*']): mixed
    {
        return Post::select($select)
            ->where('post_files.post_id', $id)
            ->leftJoin('post_files', 'post_files.post_id', 'posts.id')
            ->leftJoin('files', 'files.id', 'post_files.file_id')
            ->get();
    }

    /**
     * Return first file
     *
     * @param int $id
     * @param array $select
     * @return mixed
     */
    public function firstFile(int $id, array $select = ['path']): mixed
    {
        return Post::select(...$select)
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
     * Return wide image file
     *
     * @param int $id
     * @param array $select
     * @return mixed
     */
    public function wideFile(int $id, array $select = ['path']): mixed
    {
        return Post::select(...$select)
            ->where('posts.id', $id)
            ->where('files.type', 3)
            ->leftJoin('post_files', 'post_files.post_id', '=', 'posts.id')
            ->leftJoin('files', 'files.id', 'post_files.file_id')
            ->first();
    }

    /**
     * Find one record
     *
     * @param int $id
     * @param mixed ...$select
     * @param string $col
     * @return mixed
     */
    public function find(mixed $id, array $select = ['*'], string $col = 'posts.id'): mixed
    {
        return $this->localeInstance(...$select)->where($col, $id)->first();
    }

    /**
     * Find all records with active locale
     *
     * @param mixed ...$select
     * @return mixed
     */
    public function all(array $select = ['*']): mixed
    {
        return $this->localeInstance(...$select)->latest()->get();
    }

    /**
     * Return the saved posts of user
     *
     * @param string[] $select
     * @param null $userId
     * @return mixed
     */
    public function savedPosts($select = ['*'], $userId = null): mixed
    {
        $userId = $userId ?? auth()->id();
        return $this->localeInstance(...$select)
            ->latest('saved_posts.created_at')
            ->where('saved_posts.user_id', $userId)
            ->leftJoin('saved_posts', 'saved_posts.post_id', 'posts.id')
            ->paginate(15);
    }

    /**
     * Return if given id has children
     *
     * @param int $id
     * @return bool
     */
    public function hasChildren(int $id): bool
    {
        return DB::table('post_parents')->where('parent_id', $id)->exists();
    }

    /**
     * Return the given id's parentTree
     *
     * @param int $id
     * @param string[] $select
     * @return array
     */
    public function parentTree(int $id, $select = ['*']): array
    {
        $tree = [];
        while($post = $this->localeInstance(...$select)
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

    /**
     * Return most viewed of given id's children
     *
     * @param int|array $id
     * @param array|string[] $select
     * @param int|null $limit
     * @return mixed
     */
    public function mostViewedChildren(int|array $id, array$select = ['*'], int|null $limit = null): mixed
    {
        $query = $this->localeInstance(...$select)
            ->leftJoin('post_parents', 'post_parents.post_id', 'posts.id')
            ->latest('views')
            ->take($limit);
        if (is_array($id)){
            return $query->whereIn('post_parents.parent_id', $id)->get();
        }
        return $query->where('post_parents.parent_id', $id)->get();
    }

    /**
     * Return query instance to find children of given id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public function childrenInstance(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        $query = $this->localeInstance(...$select)
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
     * Find children of given id|ids
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public function children(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        return $this->childrenInstance($id, $select, $limit)->distinct()->get();
    }

    /**
     * Find children of given id|ids with children's count
     *
     * @param int|array $id
     * @param array $select
     * @param int|null $limit
     * @return mixed
     */
    public function childrenWithChildrenCount(int|array $id, array $select = ['*'], int|null $limit = null): mixed
    {
        //$select[] = DB::raw('(select count(*) from "post_parents" where "posts"."id" = "post_parents"."parent_id") as "children_count"');
        return $this->childrenInstance($id, $select, $limit)->withCount('children')->get();
    }

    /**
     * Return locale instance
     *
     * @param mixed ...$select
     * @return mixed
     */
    public function localeInstance(...$select): mixed
    {
        /*
        return DB::table('posts')
            ->select($select)
            ->where('active', 1)
            ->where('language', app()->getLocale())
            ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id');
        */
        return Post::select($select)
            ->where('active', 1)
            ->where('language', app()->getLocale())
            ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id');
    }
}
