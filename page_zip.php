<?php

$zipPath = getParam('path');
$password = getZipPassword($zipPath);
$wrongPassword = false;

try {
    $tmpFilePaths = tryCache('extractZip', array($zipPath, $password));
} catch (InvalidArgumentException $iae) {
    $wrongPassword = true;
}

?>

<h2><a href="<?php echo buildUrl(''); ?>">/</a><?php echo $zipPath; ?></h2>

<?php if (!$wrongPassword): ?>
    <?php require('block_tmp_files.php'); ?>
<?php else: ?>
    <?php require('page_zip_password.php'); ?>
<?php endif; ?>
