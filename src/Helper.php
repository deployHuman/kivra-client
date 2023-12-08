<?php

namespace DeployHuman\kivra;

class Helper
{
    public static function addIfNotEmpty(array &$array, $key, $value)
    {
        if (empty($value)) {
            return;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if (is_object($v) && method_exists($v, 'toArray')) {
                    $value[$k] = $v->toArray();
                }
            }
        } elseif (is_object($value) && method_exists($value, 'toArray')) {
            $value = $value->toArray();
        }

        $array[$key] = $value;
    }
}
