<?php

/**
 * Returns array value or default
 *
 * @param array $array
 * @param mixed $key
 * @param mixed $default [optional]
 *
 * @return mixed|null
 */
function array_get($array, $key, $default = null)
{
    if (array_key_exists($key, $array)) {
        return $array[$key];
    }
    return active_value($default, [$array, $key]);
}

/**
 * Calls handler for each array item
 *
 * @param array    $array
 * @param callable $handler
 *
 * ```php
 * array_each([1 => 1111, 'two' => 2222], function($key, $val) {
 *      $key; // 1
 *      $val; // 1111
 * })
 * ```
 * ```php
 * array_each([1 => 1111, 'two' => 2222], [$obj, 'addParam']);
 * ```
 *
 * @return void
 */
function array_each($array, callable $handler) {
    foreach ($array as $key => $value) {
        call_user_func($handler, $key, $value);
    }
}

/**
 * Call value if its a callable or return his
 *
 * @param mixed|callable $value
 * @param array          $args [optional]
 *
 * @return mixed
 */
function active_value($value, $args = [])
{
    if (is_callable($value)) {
        return call_user_func($value, ...$args);
    }
    return $value;
}

/**
 * Check if value between min and max values
 *
 * @param mixed $value
 * @param mixed $min
 * @param mixed $max
 *
 * @return bool
 */
function in_range($value, $min, $max)
{
    return $value >= $min && $value <= $max;
}