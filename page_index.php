<?php

$root = getParam('root');
$zipPaths = tryCache('findZipPaths', array($root));

?>

<h1><?php echo $root; ?></h1>
<h5>
    [<a href="<?php echo buildUrl('expand_all'); ?>">Expand All</a>]
    [<a href="<?php echo buildUrl('files'); ?>">Files</a>]
    [<a href="<?php echo buildUrl('videos'); ?>">Videos</a>]
    [<a href="<?php echo buildUrl('reset_config'); ?>" style="color: red">Mischief Managed</a>]
</h5>

<ul class="zips">
    <?php $l = count($zipPaths); ?>
    <?php for ($i = 0; $i < $l; $i++): ?>
        <li class="zip">
            <?php

            $zipPath = $zipPaths[$i];
            $zipPathGroup = array($zipPath);
            $password = getZipPassword($zipPath);
            $tmpFilePaths = tryCache('extractZip', array($zipPath, $password), false);
            $tmpFilePathsCount = 0;

            if (!empty($tmpFilePaths)) {
                $maxFiles = 10;
                $tmpFilePathsCount = count($tmpFilePaths);

                if ($tmpFilePathsCount > $maxFiles) {
                    $tmpFilePaths = array_slice($tmpFilePaths, 0, $maxFiles);
                } elseif (count($tmpFilePaths) === 1) {
                    while ($i + 1 < $l) {
                        $_zipPath = $zipPaths[$i + 1];
                        $_password = getZipPassword($_zipPath);
                        $_tmpFilePaths = tryCache('extractZip', array($_zipPath, $_password), false);
                        if (!empty($_tmpFilePaths) && count($_tmpFilePaths) === 1) {
                            $zipPathGroup[] = $_zipPath;
                            $tmpFilePaths = array_merge($tmpFilePaths, $_tmpFilePaths);
                            $i++;
                        } else {
                            break;
                        }
                    }
                }
            }

            ?>

            <?php foreach ($zipPathGroup as $__zipPath): ?>
                <?php

                $__fileSize = filesize(getZipPathFull($__zipPath));
                $__sizeClass = 'normal';
                if ($__fileSize < 1024 * 1024) {
                    $__sizeClass = 'small';
                } elseif ($__fileSize > 5 * 1024 * 1024) {
                    $__sizeClass = 'big';

                    if ($__fileSize > 100 * 1024 * 1024) {
                        $__sizeClass = 'huge';
                    }
                }

                ?>
                <a href="<?php echo buildUrl('zip', array('path' => $__zipPath)); ?>" class="size-<?php echo $__sizeClass; ?>">
                    <?php echo htmlentities($__zipPath); ?>
                </a>
                (<?php echo getHumanSize($__fileSize); ?><?php if (count($zipPathGroup) == 1 && $tmpFilePathsCount > 0) echo(sprintf(', %d files', $tmpFilePathsCount)); ?>)
                <br />
            <?php endforeach; ?>

            <?php if (!empty($tmpFilePaths)) require('block_tmp_files.php'); ?>
        </li>
    <?php endfor; ?>
</ul>
