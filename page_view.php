<?php

$mimeType = getMimeType(getParam('path'));
$root = getParam('root');
$zipPaths = tryCache('findZipPaths', array($root));

$prevTmpFilePaths = array();
$nextTmpFilePaths = array();
$targetFound = false;
$foundZipPath = '';
$maxFiles = 3;

foreach ($zipPaths as $_zipPath) {
    $password = getZipPassword($_zipPath);

    try {
        $_tmpFilePaths = tryCache('extractZip', array($_zipPath, $password), false);

        if (!empty($_tmpFilePaths)) {
            foreach ($_tmpFilePaths as $_tmpFilePath) {
                if ($_tmpFilePath === getParam('path')) {
                    $targetFound = true;
                    $foundZipPath = $_zipPath;
                } elseif (!$targetFound) {
                    $prevTmpFilePaths[] = $_tmpFilePath;
                    if (count($prevTmpFilePaths) > $maxFiles) {
                        array_shift($prevTmpFilePaths);
                    }
                } else {
                    $nextTmpFilePaths[] = $_tmpFilePath;
                    if (count($nextTmpFilePaths) > $maxFiles) {
                        // exit both foreach
                        array_pop($nextTmpFilePaths);
                        break 2;
                    }
                }
            }
        }
    } catch (Exception $e) {
        // ignore
    }
}

$mimeType = getMimeType(getParam('path'));
$isImage = substr($mimeType, 0, 6) === 'image/';
$isVideo = substr($mimeType, 0, 6) === 'video/';

?>

<h3><a href="<?php echo buildUrl(''); ?>">/</a>../<a href="<?php echo
    buildUrl('zip', array('path' => $foundZipPath)); ?>"><?php echo
        basename($foundZipPath); ?></a>/<?php echo basename(getParam('path')); ?></h3>

<div class="nav">
    <div class="prev">
        <?php foreach ($prevTmpFilePaths as $_tmpFilePath): ?>
            <?php require('block_nav_file.php'); ?>
        <?php endforeach; ?>
    </div>
    <div class="active">
        <?php $_tmpFilePath = getParam('path'); ?>
        <?php require('block_nav_file.php'); ?>
    </div>
    <div class="next">
        <?php foreach ($nextTmpFilePaths as $_tmpFilePath): ?>
            <?php require('block_nav_file.php'); ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="viewer">

    <?php if ($isImage): ?>
        <img src="<?php echo buildUrl('stream', array('path' => getParam('path'))); ?>" class="target"/>
    <?php else: ?>
        <video class="target video-js vjs-default-skin" controls autoplay
               width="800" height="480"
               poster="<?php echo getThumbnailUrl(getParam('path')); ?>"
               data-setup="{}">
            <source src="<?php echo buildUrl('stream', array('path' => getParam('path'))); ?>"
                    type="<?php echo $mimeType; ?>"/>
            <p class="vjs-no-js">To view this video please enable JavaScript,
                and consider upgrading to a web browser that
                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
        </video>
    <?php endif; ?>

</div>