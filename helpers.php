<?php

function getHumanSize($bytes)
{
    $unit = '';
    $value = $bytes;

    $units = array('K', 'M', 'G');
    while ($value > 1024 && count($units) > 0) {
        $value /= 1024;
        $unit = array_shift($units);
    }

    return sprintf('%.2f%s', $value, $unit);
}

function printCachingHttpHeaders()
{
    header('Cache-Control: max-age=86400');
}