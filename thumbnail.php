<?php

$tmpFilePath = getParam('path');
$mimeType = getMimeType($tmpFilePath);

switch ($mimeType) {
    case 'image/gif':
        $img = imagecreatefromgif($tmpFilePath);
        break;
    case 'image/jpeg':
        $img = imagecreatefromjpeg($tmpFilePath);
        break;
    case 'image/png':
        $img = imagecreatefrompng($tmpFilePath);
        break;
}
if (empty($img)) {
    throw new Exception(sprintf('Unable to create image for %s (mime type %s)', $tmpFilePath, $mimeType));
}

$width = imagesx($img);
$height = imagesy($img);

$targetHeight = 100;
$targetWidth = $width * $targetHeight / $height;
$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
imagecopyresampled($thumbnail, $img, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

printCachingHttpHeaders();
header('Content-Type: image/jpeg');
imagejpeg($thumbnail);
