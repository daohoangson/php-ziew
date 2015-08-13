<?php

$tmpFilePath = getParam('path');
$mimeType = getMimeType($tmpFilePath);

$fh = fopen($tmpFilePath, 'rb');
if (empty($fh)) {
    throw new Exception(sprintf('fopen(%s, rb) failed', $tmpFilePath));
}

$size = filesize($tmpFilePath);

$begin = 0;
$end = $size - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
    if (preg_match('/bytes=(?<begin>\d+)-(?<end>\d*)/i', $_SERVER['HTTP_RANGE'], $matches)) {
        $begin = intval($matches['begin']);
        if (!empty($matches['end'])) {
            $end = intval($matches['end']);
        }
    }
}

if ($begin > 0 || $end < ($size - 1)) {
    header('HTTP/1.0 206 Partial Content');
    header(sprintf('Content-Range: bytes %d-%d/%d', $begin, $end, $size));
} else {
    header('HTTP/1.0 200 OK');
}

header('Accept-Ranges: bytes');
header(sprintf('Content-Type: %s', $mimeType));
header(sprintf('Content-Length: %d', $end - $begin + 1));
header(sprintf('Last-Modified: %s GMT', gmdate('D, d M Y H:i:s', filemtime($tmpFilePath))));

$cur = $begin;
$chunk = 1024 * 1024;
fseek($fh, $begin);

while (!feof($fh) && $cur < $end && (connection_status() == 0)) {
    print(fread($fh, min($chunk, $end - $cur + 1)));
    $cur += $chunk;
    ob_flush();
    flush();
}
$status = fclose($fh);
