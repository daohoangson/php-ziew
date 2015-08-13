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

function getThumbnailUrl($tmpFilePath)
{
    $mimeType = getMimeType($tmpFilePath);

    if (substr($mimeType, 0, 6) === 'image/') {
        return buildUrl('thumbnail', array('path' => $tmpFilePath));
    }

    if (class_exists('FFMpeg\FFMpeg')
        && substr($mimeType, 0, 6) === 'video/'
    ) {
        return buildUrl('vthumbnail', array('path' => $tmpFilePath));
    }

    return '';
}

function printCachingHttpHeaders()
{
    header('Cache-Control: max-age=86400');
}