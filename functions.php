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
function _get($array, $key, $default = null)
{
    if (is_string($key) && array_key_exists($key, $array)) {
        return $array[$key];
    } else if (is_callable($key)) {
        $value = _value($key, [$array, null]);

        if ($value !== null) {
            return $value;
        }
    } else {
        return _chain($array, $key, $default);
    }
    return _value($default, [$array, $key]);
}

/**
 * Check existing key in source array
 *
 * @param array  $array
 * @param string $index
 *
 * @return bool
 */
function _exists(array $array, $index)
{
    $pos = strrpos($index, '.');

    if ($pos !== false && false === array_key_exists($index, $array)) {
        $array = _chain($array, substr($index, 0, $pos));
        $index = substr($index, $pos + 1);
    }

    return is_array($array) && array_key_exists($index, $array);
}

/**
 * Returns value from multidimensional array with path separated by '.' char
 *
 * @param array $array
 * @param mixed $key
 * @param mixed $default [optional]
 *
 * @return mixed
 */
function _chain(array $array, $key, $default = null)
{
    $pos = strrpos($key, '.');

    if ($pos !== false) {
        $array = _chain($array, substr($key, 0, $pos), []);
        $key = substr($key, $pos + 1);
    }

    if (array_key_exists($key, $array)) {
        return $array[$key];
    }
    return _value($default, $array, $key);
}

/**
 * Calls handler for each array item
 *
 * @param array    $array
 * @param callable $handler
 *
 * ```php
 * _each([1 => 1111, 'two' => 2222], function($key, $val) {
 *      $key; // 1
 *      $val; // 1111
 * })
 * ```
 * ```php
 * _each([1 => 1111, 'two' => 2222], [$obj, 'addParam']);
 * ```
 *
 * @return void
 */
function _each($array, callable $handler) {
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
function _value($value, $args = [])
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