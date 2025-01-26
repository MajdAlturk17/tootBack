<?php

namespace App\Application\UseCases\Model;

use App\Traits\SortHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IndexModelUseCase extends ModelUseCase implements ProcessModelCrud
{
    use SortHelper;
    protected array $conditions = [];
    protected array $with = [];
    protected array $columns = ['*'];
    protected string $orderBy = 'id';
    protected string $sortType = 'desc';

    /**
     * @throws \Exception
     */
    function execute(array $data = []): LengthAwarePaginator|Collection|array
    {
        $this->setConditions($data);
        $result = $this->handle($this->prepareData($data));

        return $this->afterHandling($result);
    }

    /**
     * @throws \Exception
     */
    public function handle(array $data = []): mixed
    {
        return isset($data['per_page']) ? $this->getPaginate($data['per_page']) : $this->getAll();
    }

    public function prepareData(array $data = []): array
    {
        return $data;
    }

    /**
     * @throws \Exception
     */
    private function getPaginate($perPage): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $this->conditions, $this->with, $this->columns, $this->prepareSort());
    }

    protected function getAll(): Collection
    {
        $sort = $this->prepareSortWithKeyValue();

        return $this->repository->getByAttributes($this->conditions, $this->with, $this->columns, $sort['sort'], $sort['sort_type']);
    }

    protected function setConditions(array $conditions): void
    {
        if(count($conditions) > 0)
            $this->conditions = $conditions;
        else $this->conditions = [];
    }

}
