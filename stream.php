<?php

$tmpFilePath = getParam('path');
$mimeType = getMimeType($tmpFilePath);

printCachingHttpHeaders();
header(sprintf('Content-Type: %s', $mimeType));
header(sprintf('Content-Length: %d', filesize($tmpFilePath)));

$fh = fopen($tmpFilePath, 'rb');
if (empty($fh)) {
    throw new Exception(sprintf('fopen(%s, rb) failed', $tmpFilePath));
}

while (!feof($fh)) {
    echo fread($fh, 1024 * 1024);
    flush();
}
$status = fclose($fh);
