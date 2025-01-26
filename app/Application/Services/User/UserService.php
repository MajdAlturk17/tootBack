<?php

namespace App\Application\Services\User;
use App\Application\UseCases\User\DestroyUserUseCase;
use App\Application\UseCases\User\GetUserUseCase;
use App\Application\UseCases\User\IndexUserUseCase;
use App\Application\UseCases\User\StoreUserUseCase;
use App\Application\UseCases\User\UpdateUserUseCase;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\User\UserRepository;
use App\Traits\PaginationHelper;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UserService extends AuthService
{

    use PaginationHelper;

    protected StoreUserUseCase $storeUserUseCase;
    protected UpdateUserUseCase $updateUserUseCase;
    protected GetUserUseCase $getUserUseCase;
    protected DestroyUserUseCase $destroyUserUseCase;
    protected IndexUserUseCase $indexUserUseCase;


    protected array $headers = ['id', 'name'];

    public function __construct(UserRepository $userRepository,
                                StoreUserUseCase      $storeUserUseCase,
                                UpdateUserUseCase     $updateUserUseCase,
                                GetUserUseCase        $getUserUseCase,
                                DestroyUserUseCase    $destroyUserUseCase,
                                IndexUserUseCase     $indexUserUseCase)
    {
        parent::__construct($userRepository);
        $this->storeUserUseCase = $storeUserUseCase;
        $this->updateUserUseCase = $updateUserUseCase;
        $this->getUserUseCase = $getUserUseCase;
        $this->destroyUserUseCase = $destroyUserUseCase;
        $this->indexUserUseCase = $indexUserUseCase;
    }


    /**
     * @throws \Exception
     */
    public function index(array $data): AnonymousResourceCollection|LengthAwarePaginator
    {
        $result = $this->indexUserUseCase->execute($data);

        return !isset($data['per_page']) ? UserResource::collection($result) :
            $this->getPaginatedData($this->headers,$result, UserResource::class);
    }

     /**
     * @param array $data
     * @return UserResource
     */
    public function store(array $data): UserResource
    {
        $result = $this->storeUserUseCase->execute($data);
        return new UserResource($result);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        $data['id'] = $id;
        return $this->updateUserUseCase->execute($data);
    }

    /**
     * @param int $id
     * @return UserResource
     */
    public function show(int $id): UserResource
    {
        $source = $this->getUserUseCase->execute(['id' => $id]);
        return new UserResource($source);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id): mixed
    {
        return $this->destroyUserUseCase->execute(['id' => $id]);
    }

    public function registerAdmin(array $data): UserResource
    {
        return $this->store($data);
    }

    /**
     * @throws ValidationException
     */
    public function loginAdmin(array $data): array
    {
        return $this->login($data);
    }
}
