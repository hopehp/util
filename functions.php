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