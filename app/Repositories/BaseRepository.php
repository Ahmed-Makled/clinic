<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Base Repository Class
 * 
 * This class serves as the foundation for all repository classes in the application.
 * Repositories are responsible for data access and abstraction of database operations.
 */
class BaseRepository
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all resources.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Get paginated resources.
     *
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage, $columns);
    }

    /**
     * Create a new resource.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing resource.
     *
     * @param Model|int $model
     * @param array $data
     * @return bool
     */
    public function update($model, array $data): bool
    {
        if (!$model instanceof Model) {
            $model = $this->find($model);
        }

        return $model->update($data);
    }

    /**
     * Delete a resource.
     *
     * @param Model|int $model
     * @return bool
     */
    public function delete($model): bool
    {
        if (!$model instanceof Model) {
            $model = $this->find($model);
        }

        return $model->delete();
    }

    /**
     * Find a resource by id.
     *
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        $model = $this->model->with($relations)->findOrFail($id, $columns);

        if (!empty($appends)) {
            $model->append($appends);
        }

        return $model;
    }

    /**
     * Find a resource by a specific field.
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findByField(string $field, $value, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->where($field, $value)->first($columns);
    }

    /**
     * Find multiple resources by a specific field.
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function findAllByField(string $field, $value, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->where($field, $value)->get($columns);
    }
}

