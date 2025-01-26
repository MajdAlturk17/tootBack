<?php

namespace App\Traits;

/**
 * Handles dynamic array validation rules for form requests or controllers.
 *
 * Provides methods to dynamically generate validation rules for array inputs,
 * facilitating base validation as well as extended validation with additional
 * rules applied to each element of the array.
 *
 * @author Ali Monther | ali.monther97@gmail.com
 * @version 1.0
 */
trait HandlesArrayValidation
{
    /**
     * Basic validation rules for an array and its items.
     *
     * @param string $arrayKey The key of the array in the request data.
     * @param string $itemKey The key of the items inside the array.
     * @param bool $checkUniqueInRequest Set true to check uniqueness within the request data.
     * @return array The validation rules.
     */
    public function validateArrayElements(string $arrayKey, string $itemKey, bool $checkUniqueInRequest = true): array
    {
        $rules = [
            $arrayKey => 'required|array',
            $arrayKey . '.*.' . $itemKey => ['required', 'string', 'unique:' . $arrayKey . ',' . $itemKey],
        ];

        if ($checkUniqueInRequest) {
            $rules[$arrayKey . '.*.' . $itemKey][] = $this->uniqueValidationClosure($arrayKey, $itemKey);
        }

        return $rules;
    }

    /**
     * Extend basic validation rules with additional custom rules for each item.
     *
     * @param string $arrayKey The key of the array in the request data.
     * @param string $itemKey The key of the items inside the array.
     * @param array $additionalRules Additional validation rules to apply to each item.
     * @param bool $checkUniqueInRequest Set true to check uniqueness within the request data.
     * @return array The extended validation rules.
     */
    public function validateArrayElementsWithExtras(string $arrayKey, string $itemKey, array $additionalRules, bool $checkUniqueInRequest = true): array
    {
        $rules = $this->validateArrayElements($arrayKey, $itemKey, $checkUniqueInRequest);

        // Append additional rules for each item in the array
        foreach ($additionalRules as $key => $value) {
            $rules[$arrayKey . '.*.' . $key] = $value;
        }

        return $rules;
    }

    /**
     * Provides a closure for checking unique values within the request.
     *
     * @param string $arrayKey
     * @param string $itemKey
     * @return \Closure
     */
    protected function uniqueValidationClosure(string $arrayKey, string $itemKey): \Closure
    {

        return function ($attribute, $value, $fail) use ($arrayKey, $itemKey) {
            if ($this->hasDuplicateInRequest($arrayKey, $itemKey, $value)) {
                $fail("The {$itemKey} must be unique. '{$value}' is duplicated.");
            }
        };
    }

    /**
     * Check for duplicates in the request data for a specific key.
     *
     * @param string $arrayKey
     * @param string $itemKey
     * @param mixed $value
     * @return bool
     */
    protected function hasDuplicateInRequest(string $arrayKey, string $itemKey, mixed $value): bool
    {
        $allItems = collect($this->input($arrayKey, []));

        $itemCount = $allItems->where($itemKey, $value)->count();

        return $itemCount > 1;
    }
}
