<?php

$root = getParam('root');
$zipPaths = tryCache('findZipPaths', array($root));
$tmpFilePaths = array();

foreach ($zipPaths as $zipPath) {
    $password = getZipPassword($zipPath);

    try {
        $_tmpFilePaths = tryCache('extractZip', array($zipPath, $password), false);

        if (!empty($_tmpFilePaths)) {
            $tmpFilePaths = array_merge($tmpFilePaths, $_tmpFilePaths);
        }
    } catch (Exception $e) {
        // ignore
    }
}

foreach (array_keys($tmpFilePaths) as $i) {
    $mimeType = getMimeType($tmpFilePaths[$i]);
    if (substr($mimeType, 0, 6) !== 'video/') {
        unset($tmpFilePaths[$i]);
    }
}
$tmpFilePaths = array_values($tmpFilePaths);

?>

<h2><a href="<?php echo buildUrl(''); ?>">/</a><a href="<?php echo
    buildUrl('files'); ?>">Files</a>/Videos (<?php echo(count($tmpFilePaths)); ?>)</h2>

<?php require('block_tmp_files.php'); ?>