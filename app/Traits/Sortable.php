<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * Apply sorting to the query by the given column and direction.
     *
     * @param Builder $query
     * @param string $sortBy
     * @param string $sortDirection
     * @return Builder
     */
    public function applySorting(Builder $query, string $sortBy, string $sortDirection = 'asc'): Builder
    {
        // Check if the column contains a relation
        if (str_contains($sortBy, '.')) {
            [$relation, $column] = explode('.', $sortBy);

            // Join the relation and sort by the related column
            $this->joinRelation($query, $relation)
                ->orderBy($column, $sortDirection);
        } else {
            // Sort by the column directly on the main table
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query;
    }

    /**
     * Helper method to join a relation.
     *
     * @param Builder $query
     * @param string $relation
     * @return Builder
     */
    protected function joinRelation(Builder $query, string $relation): Builder
    {
        $relationInstance = $this->model->{$relation}();
        $relatedTable = $relationInstance->getRelated()->getTable();
        $foreignKey = $relationInstance->getForeignKeyName();
        $localKey = $relationInstance->getLocalKeyName();

        $query->leftJoin($relatedTable, "{$relatedTable}.{$foreignKey}", '=', "{$this->model->getTable()}.{$localKey}");

        return $query;
    }
}



