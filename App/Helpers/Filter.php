<?php

namespace app\Helpers;

class Filter {
    public static function getInt($in)
    {
        return filter_var($in, FILTER_SANITIZE_NUMBER_INT);
    }
    public static function getFloat($in)
    {
        return filter_var($in, FILTER_SANITIZE_NUMBER_FLOAT);
    }
    public static function getString($in)
    {
        return filter_var($in, FILTER_SANITIZE_STRING);
    }
}
