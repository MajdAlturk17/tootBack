<?php
/**
 * StringHelper Trait
 *
 * This trait provides helper methods for working with strings, including converting cases and adding spaces.
 *
 * @author Ali Monther / ali.monther97@gmail.com
 */
namespace App\Traits;

trait StringHelper
{

    /**
     * Convert a camelCase, snake_case, or kebab-case string to a spaced format.
     *
     * @param string $string The input string to convert.
     *
     * @return string The spaced format version of the provided string.
     */
    protected static function toSpaced(string $string): string
    {

        $string = str_replace(['_', '-'], ' ', $string);
        return preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);

    }


    /**
     * Convert an enum key (name) to snake case.
     *
     * @param string $name The name to convert to snake case.
     *
     * @return string The snake case version of the provided name.
     */
    protected static function toSnakeCase(string $name): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }


    /**
     * Convert a snake_case or kebab-case string to camelCase.
     *
     * @param string $string The input string to convert.
     *
     * @return string The camelCase version of the provided string.
     */
    protected static function toCamelCase(string $string): string
    {
        $string = str_replace(['_', '-'], ' ', $string);
        $string = preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);
        $string = ucwords($string);
        return lcfirst(str_replace(' ', '', $string));
    }

    /**
     * Convert a snake_case or kebab-case string to PascalCase.
     *
     * @param string $string The input string to convert.
     *
     * @return string The PascalCase version of the provided string.
     */
    protected static function toPascalCase(string $string): string
    {
        $string = str_replace(['_', '-'], ' ', $string);
        $string = ucwords($string);
        return str_replace(' ', '', $string);
    }
}
