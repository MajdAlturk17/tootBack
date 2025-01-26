<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait Searchable
 *
 * Provides search functionality for Eloquent models.
 *
 * @package App\Infrastructure\Repositories\Traits
 */
trait Searchable
{
    /**
     * Perform a search on the model using specified columns and conditions.
     *
     * @param string $keyword The keyword to search for.
     * @param array $columns The columns to search in.
     * @param string $condition The condition to use for searching (default: 'like').
     * @param array|null $with Relationships to eager load.
     * @param array|null $order Columns to order the results by.
     * @param array $columnsToSelect Columns to select in the results.
     * @param int|null $perPage Number of results per page (for pagination).
     */
    public function applySearch(
        string $keyword,
        array  $columns,
        string $condition = 'like',
        array $additionalConditions = [],
        array $joins = [],
        ?array $with = null,
        array  $order = null,
        array  $columnsToSelect = ['*'],
        ?int   $perPage = null
    ): Collection|LengthAwarePaginator|array
    {
        $query = $this->model->newQuery();

        $this->applyJoins($query,$joins);
        $this->applySearchCondition($query, $keyword, $columns, $condition);
        $this->applyWithRelations($query, $with);
        $this->applyAdditionalConditions($query,$additionalConditions);
        $this->applyOrder($query, $order);
        return $this->getResults($query, $columnsToSelect, $perPage);
    }

    /**
     * Perform the final query execution with dynamic conditions.
     *
     * @param Builder $query The query builder instance.
     * @param array $conditions Additional conditions to apply.

     */
    public function applyAdditionalConditions(
        Builder $query,
        array   $conditions,
    ): void
    {
        foreach ($conditions as $condition) {
            [$column, $operator, $value] = $condition;
            if ($operator === 'is' && $value === null) {
                $query->whereNull($column);
            } else {
                $query->where($column, $operator, $value);
            }
        }

    }


    private function applyJoins(Builder $query, array $joins): void
    {
        foreach ($joins as $join) {
            [$table, $first, $operator, $second] = $join;
            $query->join($table, $first, $operator, $second);
        }
    }

    /**
     * Apply the search condition to the query.
     *
     * @param Builder $query The query builder instance.
     * @param string $keyword The keyword to search for.
     * @param array $columns The columns to search in.
     * @param string $condition The condition to use for searching.
     * @return void
     */
    private function applySearchCondition(Builder $query, string $keyword, array $columns, string $condition): void
    {
        $query->where(function ($q) use ($keyword, $columns, $condition) {
            foreach ($columns as $column) {
                if ($condition === 'like') {
                    $q->orWhere($column, 'like', '%' . $keyword . '%');
                } else {
                    $q->orWhere($column, $condition, $keyword);
                }
            }
        });
    }

    /**
     * Apply eager loading of relationships to the query.
     *
     * @param Builder $query The query builder instance.
     * @param array|null $with Relationships to eager load.
     * @return void
     */
    private function applyWithRelations(Builder $query, ?array $with): void
    {
        if ($with) {
            $query->with($with);
        }
    }

    /**
     * Apply ordering to the query.
     *
     * @param Builder $query The query builder instance.
     * @param array|null $order Columns to order the results by.
     * @return void
     */
    private function applyOrder(Builder $query, ?array $order): void
    {
        if ($order) {
            foreach ($order as $key => $sort) {
                $query->orderBy($key, $sort);
            }
        }
    }

    /**
     * Retrieve the results of the query, either paginated or all.
     *
     * @param Builder $query The query builder instance.
     * @param array $columnsToSelect Columns to select in the results.
     * @param int|null $perPage Number of results per page (for pagination).
     * @return Collection|LengthAwarePaginator|array
     */
    private function getResults(Builder $query, array $columnsToSelect, ?int $perPage): Collection|LengthAwarePaginator|array
    {
        if ($perPage) {
            return $query->paginate($perPage, $columnsToSelect);
        } else {
            return $query->get($columnsToSelect);
        }
    }
}
