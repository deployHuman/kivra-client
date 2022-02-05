<?php

namespace DeployHuman\kivra;

class Validation

{

    static function email($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    public static function base64(string $data): bool
    {
        return (bool) mb_ereg_match('^([A-Za-z0-9+/]{4})*([A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{2}==)?$', $data);
    }

    public static function imageMinDimensions(string $data, int $min_width = 1, int $min_height = 1): bool
    {
        $size = getimagesizefromstring($data);
        if (!$size) return false;
        if ($size[0] < $min_width  || $size[1] < $min_height) return false;
        return true;
    }
}
