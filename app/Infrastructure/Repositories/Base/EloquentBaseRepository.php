<?php

namespace App\Infrastructure\Repositories\Base;

use App\Infrastructure\Repositories\Base\Search\SearchableRepository;
use App\Traits\FilterHelper;
use App\Traits\Searchable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

/**
 * Class EloquentBaseRepository
 *
 * @package App\Repositories\Eloquent
 */
abstract class EloquentBaseRepository implements BaseRepository
{

    protected Model $model;
    public string $filterType = '';

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $function
     * @param $args
     * @return mixed
     */
    public function __call($function, $args)
    {
        $functionType = strtolower(substr($function, 0, 3));
        $propName = lcfirst(substr($function, 3));
        switch ($functionType) {
            case 'get':
                if (property_exists($this, $propName)) {
                    return $this->$propName;
                }
                break;
            case 'set':
                if (property_exists($this, $propName)) {
                    $this->$propName = $args[0];
                }
                break;
        }
    }

    public function findOrFail(int $id, ?array $with = null, ?array $order = null): ?Model
    {
        $model = $this->find($id, $with, $order);
        return $model ?? abort(404);
    }

    /**
     * @param int $id
     * @param array|null $with
     * @param array|null $order
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();

        $this->processQuery($query,$with,$order);

        return $query->find($id, $columns);
    }

    /**
     * @param array $columns
     * @param array|null $with
     * @return Collection
     */
    public function all(array $columns = ['*'], ?array $with = null): Collection
    {
        $query = $this->model->newQuery();

        $this->processQuery($query,$with);

        return $query->orderBy('id', 'DESC')->get($columns);
    }

    /**
     * @param int $perPage
     * @param array|null $conditions
     * @param array|null $with
     * @param array $columns
     * @param null $order
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?array $conditions = null, ?array $with = null, array $columns = ['*'], $order = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator|LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        $this->processQuery($query, $with);

        if (!is_null($conditions)) {
            $query->where($conditions);
        }

        if (Request::get('filters') && strlen(Request::get('filters')) > 2 && $this->canRunFilter()) {
            $filters = $this->resolveFiltersArray(Request::get('filters'));
            $query->filterBy($filters);
            $this->filterType = '';
        }

        $sorts = json_decode(Request::get('sorts'));

        if ($sorts && isset($sorts->sort) && isset($sorts->sort_type)) {
            $query = $this->applySorting($query, $sorts->sort, $sorts->sort_type);
        } elseif ($order && is_array($order)) {
            foreach ($order as $key => $sort) {
                // Check if the sort key contains a dot, indicating a relation
                if (strpos($key, '.') !== false) {
                    [$relation, $column] = explode('.', $key);

                    // Assuming the relation is a hasOne or belongsTo relationship
                    // Get the related model instance
                    $relatedModel = $this->model->$relation()->getRelated();

                    // Get the foreign key and local key for the relationship
                    $foreignKey = $this->model->$relation()->getForeignKeyName();
                    $localKey = $this->model->$relation()->getQualifiedParentKeyName();

                    // Join the related table
                    $query->leftJoin($relatedModel->getTable(), "{$this->model->getTable()}.id", '=', "{$relatedModel->getTable()}.{$foreignKey}");

                    // Apply the order by clause
                    $query->orderBy("{$relatedModel->getTable()}.{$column}", $sort);
                } else {
                    // Apply the order by clause directly on the main model
                    $query->orderBy($key, $sort);
                }
            }
        }
//        if ($order && is_array($order)) {
//            foreach ($order as $key => $sort) {
//                $query->orderBy($key, $sort);
//            }
//        }

        return $query->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param array $conditions
     * @return bool
     */
    public function update(array $data, array $conditions): bool
    {
        return $this->model->where($conditions)->update($data);
    }

    /**
     * @param array $conditions
     * @param array $data
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $data): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    /**
     * @param array $conditions
     * @return bool|null
     */
    public function destroy(array $conditions): ?bool
    {
        return $this->model->where($conditions)->delete();
    }

    /**
     * @param array $values
     * @param string $column
     * @return bool|null
     */
    public function destroyMany(array $values, string $column='id'): ?bool
    {
        return $this->model->whereIn($column,$values)->delete();
    }

    /**
     * @param string $slug
     * @param array|null $with
     * @param array $columns
     * @return Model|null
     */
    public function findBySlug(string $slug, ?array $with = null, array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();

        $this->processQuery($query,$with);

        return $query->where('slug', $slug)->first($columns);
    }

    /**
     * @param array $attributes
     * @param array|null $with
     * @param array|null $order
     * @param array $columns
     * @return Model|null
     */
    public function findByAttributes(array $attributes, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model
    {
        $query = $this->buildQueryByAttributes($attributes);

        $this->processQuery($query,$with,$order);

        return $query->first($columns);
    }

    /**
     * Build Query to catch resources by an array of attributes and params
     * @param array $attributes
     * @param null|string $orderBy
     * @param string $sortOrder
     * @return Builder
     */
    private function buildQueryByAttributes(array $attributes, ?string $orderBy = null, string $sortOrder = 'asc'): Builder
    {
        $query = $this->model->query();

        foreach ($attributes as $field => $value) {
            $query = $query->where($field, $value);
        }

        if (null !== $orderBy) {
            $query->orderBy($orderBy, $sortOrder);
        }

        return $query;
    }

    /**
     * @param array $attributes
     * @param array|null $with
     * @param array $columns
     * @param string|null $orderBy
     * @param string $sortOrder
     * @return Collection
     */
    public function getByAttributes(array $attributes, ?array $with = null, array $columns = ['*'], ?string $orderBy = null, string $sortOrder = 'asc'): Collection
    {
        $query = $this->buildQueryByAttributes($attributes, $orderBy, $sortOrder);

        $this->processQuery($query,$with);

        return $query->get($columns);
    }

    /**
     * @param array $ids
     * @param array|null $with
     * @param array $columns
     * @return Collection
     */
    public function findByMany(array $ids, ?array $with = null, array $columns = ['*']): Collection
    {
        $query = $this->model->query();

        $query->whereIn("id", $ids);

        $this->processQuery($query,$with);

        return $query->get($columns);
    }

    protected function processQuery(&$query,$with=null,$order=null): void
    {
        if ($with) {
            $query->with($with);
        }

        if (method_exists($this->model, 'locales')) {
            $query->withLocale();
        }

        if ($order && is_array($order)) {
            foreach ($order as $key => $sort) {
                $query->orderBy($key, $sort);
            }
        }
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param array $data
     * @param array $updateColumns
     * @return int
     */
    public function upsert(array $data, array $updateColumns): int
    {
        // The upsert method is not available on all database drivers,
        // so you may want to check if it's supported for your database.
        // This example assumes you're using MySQL.
        return $this->model->upsert($data, $updateColumns);
    }

    public function getFilterType(): string
    {
        return $this->filterType;
    }

    public function setFilterType(string $filterType): static
    {
        $this->filterType = $filterType;
        return $this;
    }


    public function search(string $keyword, array $columns, string $condition = 'like', ?array $with = null, array $order = null, array $columnsToSelect = ['*'], ?int $perPage = null): LengthAwarePaginator|Collection
    {
        return $this->applySearch($keyword,$columns,$condition,$with,$order,$columnsToSelect,$perPage);
    }

    private function canRunFilter(): bool
    {

        if(Request::get('filters') && strlen(Request::get('filters')) > 2){
            $requiredModelName = Request::get('filter_type') ?? null;
            if (!$requiredModelName || strlen($requiredModelName) <= 2) abort('400','filter_type param is required when using filters');
            return $requiredModelName === $this->filterType;
        }
        return true;

    }


}
