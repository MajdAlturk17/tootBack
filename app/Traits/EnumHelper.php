<?php
/**
 * @file
 * EnumHelper Trait
 *
 * @description A trait providing helper methods for working with enums, including retrieving enum keys (names)
 *              and getting enum values based on names with options to replace underscores or spaces.
 *
 */
namespace App\Traits;

use ValueError;

trait EnumHelper
{
    /**
     * Convert a camel case string to a space-separated string.
     *
     * @param string $string  The camel case string to convert.
     *
     * @return string  The space-separated string.
     */
    private static function convertCamelCaseToSpace(string $string): string
    {
        // Add a space before each uppercase letter except the first one
        return preg_replace('/(?<!^)([A-Z])/', ' $1', $string);
    }

    /**
     * Get an array of enum keys (names) with an option to replace underscores with spaces.
     *
     * @param bool $replaceUnderscore  Whether to replace underscores with spaces in the keys.
     *
     * @return array  An array of enum keys (names).
     */
    public static function keys(bool $replaceUnderscore = false): array
    {
        // Retrieve an array of enum names using collection mapping
        $names = collect(self::cases())->map->name->all();

        // If $replaceUnderscore is true, replace underscores with spaces in each name
        if ($replaceUnderscore) {
            $names = array_map(fn($name) => str_replace('_', ' ', $name), $names);
        }

        return $names;
    }

    /**
     * Get the enum value based on the provided name, with an option to replace spaces.
     *
     * @param string $name        The name to search for in enum cases.
     * @param bool $replaceSpace  Whether to replace spaces with underscores in the provided name.
     *
     * @return string  The enum value as a string.
     *
     * @throws ValueError  If the provided name is not a valid backing value for the enum.
     */
    public static function fromName(string $name, bool $replaceSpace = false): string
    {
        // Replace spaces with underscores if $replaceSpace is true
        $name =  $replaceSpace ?  str_replace(' ', '_', $name): $name;

        // Iterate over enum cases to find a match based on the modified or original name
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status->value;
            }
        }

        // Throw an exception if no match is found
        throw new ValueError("$name is not a valid backing value for enum " . self::class );
    }

    /**
     * Get an array of enum keys (names) with camel case converted to space-separated names.
     *
     * @return array  An array of enum names with spaces.
     */
    public static function spacedKeys(): array
    {
        // Retrieve an array of enum names using collection mapping
        $names = collect(self::cases())->map->name->all();

        // Convert each camel case name to a space-separated name
        return array_map(fn($name) => self::convertCamelCaseToSpace($name), $names);
    }


    /**
     * Get an array of label and value pairs for the enum, where the label is the space-separated enum name.
     *
     * @return array  An array of associative arrays with 'label' (spaced name) and 'value' (enum name).
     */
    public static function toLabelValueArray($withConvertToCamelCase = true): array
    {
        return array_map(function ($case) use ($withConvertToCamelCase) {
            return [
                'label' => $withConvertToCamelCase ?  self::convertCamelCaseToSpace($case->name): $case->name,
                'value' => $case->name,
            ];
        }, self::cases());
    }
}
