<?php

$root = getParam('root');
$zipPaths = tryCache('findZipPaths', array($root));

?>

<h1><?php echo $root; ?></h1>

<ul class="zips">
    <?php foreach ($zipPaths as $zipPath): ?>
        <li class="zip">
            <?php

            $fileSize = filesize(getZipPathFull($zipPath));
            $sizeClass = 'normal';
            if ($fileSize < 1024 * 1024) {
                $sizeClass = 'small';
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $sizeClass = 'big';

                if ($fileSize > 100 * 1024 * 1024) {
                    $sizeClass = 'huge';
                }
            }

            $password = getZipPassword($zipPath);
            $tmpFilePaths = tryCache('extractZip', array($zipPath, $password), false);

            ?>

            <a href="<?php echo buildUrl('zip', array('path' => $zipPath)); ?>" class="size-<?php echo $sizeClass; ?>">
                <?php echo htmlentities($zipPath); ?>
            </a>

            (<?php echo getHumanSize($fileSize); ?><?php if (!empty($tmpFilePaths) > 0) echo(sprintf(', %d files', count($tmpFilePaths))); ?>)

            <?php
                if (!empty($tmpFilePaths)) {
                    if (count($tmpFilePaths) > 5) {
                        $tmpFilePaths = array_slice($tmpFilePaths, 0, 5);
                    }

                    require('block_tmp_files.php');
                }
            ?>
        </li>
    <?php endforeach; ?>
</ul>
