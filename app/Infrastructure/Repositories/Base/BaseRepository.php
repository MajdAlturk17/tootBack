<?php

/**
 * BaseRepository Interface
 *
 * PHP version 8.1
 * LARAVEL version 10.10
 *
 * @description This interface defines the contract for a generic repository, providing common
 *  CRUD operations and query methods for interacting with data storage.
 * @category Repository
 * @package  App\Infrastructure\Repositories\Base
 * @author   Ali Monther / ali.monther97@gmail.com
 */
namespace App\Infrastructure\Repositories\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{

    /**
     * @param int $id
     * @param array|null $with
     * @param array|null $order
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model;


    /**
     * @param int $id
     * @param array|null $with
     * @param array|null $order
     * @return Model|null
     */
    public function findOrFail(int $id, ?array $with = null, ?array $order = null): ?Model;

    /**
     * @param array $columns
     * @param array|null $with
     * @return Collection
     */
    public function all(array $columns = ['*'], ?array $with = null): Collection;

    /**
     * @param int $perPage
     * @param array|null $conditions
     * @param array|null $with
     * @param array $columns
     * @param null $order
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?array $conditions = null, ?array $with = null, array $columns = ['*'], $order = null): LengthAwarePaginator;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param array $data
     * @param array $conditions
     * @return bool
     */
    public function update(array $data, array $conditions): bool;

    /**
     * @param array $data
     * @param array $conditions
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $data): Model;

    /**
     * @param array $conditions
     * @return bool|null
     */
    public function destroy(array $conditions): ?bool;

    /**
     * @param array $values
     * @param string $column
     * @return bool|null
     */
    public function destroyMany(array $values, string $column='id'): ?bool;

    /**
     * @param string $slug
     * @param array|null $with
     * @param array $columns
     * @return Model|null
     */
    public function findBySlug(string $slug, ?array $with = null, array $columns = ['*']): ?Model;

    /**
     * @param array $attributes
     * @param array|null $with
     * @param array|null $order
     * @param array $columns
     * @return Model|null
     */
    public function findByAttributes(array $attributes, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model;

    /**
     * @param array $ids
     * @param array|null $with
     * @param array $columns
     * @return Collection
     */
    public function findByMany(array $ids, ?array $with = null, array $columns = ['*']): Collection;

    /**
     * @param array $attributes
     * @param array|null $with
     * @param array $columns
     * @param string|null $orderBy
     * @param string $sortOrder
     * @return Collection
     */
    public function getByAttributes(array $attributes, ?array $with = null, array $columns = ['*'], ?string $orderBy = null, string $sortOrder = 'asc'): Collection;


    public function insert(array $data);

    public function upsert(array $data, array $updateColumns): int;

    public function setFilterType(string $filterType): static;

}

