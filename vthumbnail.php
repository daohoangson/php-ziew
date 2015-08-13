<?php

$tmpFilePath = getParam('path');

$tmp = tempnam(sys_get_temp_dir(), 'vthumbnail');

$ffmpeg = FFMpeg\FFMpeg::create([
    'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
    'ffprobe.binaries' => '/usr/local/bin/ffprobe',
]);
$video = $ffmpeg->open($tmpFilePath);
$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))
    ->save($tmp);

$img = imagecreatefromjpeg($tmp);
$width = imagesx($img);
$height = imagesy($img);

$targetHeight = min($height, 480);
$targetWidth = $width * $targetHeight / $height;
$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
imagecopyresampled($thumbnail, $img, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

$buttonX = $targetWidth / 2;
$buttonY = $targetHeight / 2;
$buttonRadius = min($targetWidth, $targetHeight) / 2;
$buttonBgColor = imagecolorallocatealpha($thumbnail, 255, 255, 255, 100);
$buttonFgColor = imagecolorallocatealpha($thumbnail, 0, 0, 0, 150);
imagefilledellipse($thumbnail, $buttonX, $buttonY, $buttonRadius, $buttonRadius, $buttonBgColor);
imagefilledpolygon($thumbnail, array(
    $buttonX - $buttonRadius / 6, $buttonY - $buttonRadius / 4,
    $buttonX - $buttonRadius / 6, $buttonY + $buttonRadius / 4,
    $buttonX + $buttonRadius / 4, $buttonY,
), 3, $buttonFgColor);

printCachingHttpHeaders();
header('Content-Type: image/jpeg');
imagejpeg($thumbnail);

