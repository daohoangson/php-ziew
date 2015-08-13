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

?>

<h2><a href="<?php echo buildUrl(''); ?>">/</a>Files</h2>

<?php require('block_tmp_files.php'); ?>