<?php

namespace App\Repositories\Interfaces;

interface PostRepositoryInterface
{
    public function insertParents(int $id, array $parentIds): bool;

    public function insertRelations(int $id, array $relationIds): bool;

    public function insertFiles(int $id, array $fileIds): bool;

    public function updatePostParents(int $id, array $parentIds): bool;

    public function updatePostRelations(int $id, array $relationIds): bool;

    public function updatePostFiles(int $id, array $fileIds): bool;

    public function insertTranslations(int $id, $data): bool;

    public function updateTranslations(int $id, $data): void;

    public function selectables(): mixed;

    public function parents(int $id, array $select = ['*']): mixed;

    public function relations(int $id, array $select = ['*'], int|null $limit = null): mixed;

    public function files(int $id, array $select = ['*']): mixed;

    public function firstFile(int $id): mixed;

    public function find(int $id, ...$select): mixed;

    public function findByUrl(string $url, ...$select): mixed;

    public function all(array $select = ['*']): mixed;

    public function savedPosts($select = ['*'], $userId = null): mixed;

    public function hasChildren(int $id): bool;

    public function parentTree(int $id, $select = ['*']): array;

    public function mostViewedChildren(int|array $id, array$select = ['*'], int|null $limit = null): mixed;

    public function childrenInstance(int|array $id, array $select = ['*'], int|null $limit = null): mixed;

    public function children(int|array $id, array $select = ['*'], int|null $limit = null): mixed;

    public function childrenWithChildrenCount(int|array $id, array $select = ['*'], int|null $limit = null): mixed;

    public function localeInstance(...$select): mixed;
}
