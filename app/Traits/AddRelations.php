<?php
/**
 * Trait AddRelations
 *
 * This trait provides methods to add related data to an array based on the loaded relationships.
 * It includes methods to transform collections and individual resources using specified resource classes.
 *
 * @author Ali Monther | ali.monther97@gmail.com
 */

namespace App\Traits;

trait AddRelations
{
    protected $data = [];

    /**
     * Add related data to the given array based on the loaded relationship.
     *
     * @param string $relation     The name of the relationship.
     * @param string $resource     The resource class to use for transformation.
     * @param string $key          The key to use for the related data in the resulting array.
     * @param bool   $isCollection Indicates whether the relationship is a collection or a single resource.
     *
     * @return $this
     */
    protected function addRelation($relation, $resource, string $key, bool $isCollection = true): self
    {
        if ($this->relationLoaded($relation)) {
            $this->data[$key] = $isCollection ?
                $this->transformCollection($this->$relation, $resource) :
                $this->transformResource($this->$relation, $resource);
        }

        return $this;
    }

    /**
     * Transform a collection using the specified resource class.
     *
     * @param mixed  $data     The collection data to transform.
     * @param string $resource The resource class to use for transformation.
     *
     * @return mixed The transformed collection.
     */
    protected function transformCollection(mixed $data, $resource): mixed
    {
        return $resource::collection($data);
    }

    /**
     * Transform an individual resource using the specified resource class.
     *
     * @param array  $data     The resource data to transform.
     * @param string $resource The resource class to use for transformation.
     *
     * @return mixed The transformed resource.
     */
    protected function transformResource(mixed $data, $resource): mixed
    {
        return new $resource($data);
    }

    /**
     * Get the final data array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Merge the existing data array with additional data.
     *
     * @param array $additionalData
     */
    protected function setParentData(array $additionalData): self
    {
        $this->data = $additionalData + $this->data;

        return $this;
    }

    /**
     * Add a custom item to the data array.
     *
     * @param string $key   The key to use for the custom item in the resulting array.
     * @param mixed  $value The value of the custom item.
     * @param bool $condition The condition to add the item.
     * @param mixed $defaultValue The default value if the condition value is false.
     * @return $this
     */
    protected function addItem(string $key, mixed $value, bool $condition = true,mixed $defaultValue = null): self
    {
        $condition ? $this->data[$key] = $value : $this->data[$key] = $defaultValue;
        return $this;

    }
}

