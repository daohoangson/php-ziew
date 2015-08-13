<ul class="files">
    <?php foreach ($tmpFilePaths as $tmpFilePath): ?>
        <li class="file">
            <a href="<?php echo buildUrl('stream', array('path' => $tmpFilePath)); ?>" target="_blank">
                <?php

                $mimeType = getMimeType($tmpFilePath);
                if ($mimeType === 'application/pxm') {
                    continue;
                }

                $isImage = substr($mimeType, 0, 6) === 'image/';
                if (class_exists('FFMpeg\FFMpeg')) {
                    $isVideo = substr($mimeType, 0, 6) === 'video/';
                }

                ?>

                <?php if (!empty($isImage)): ?>
                    <img src="<?php echo buildUrl('thumbnail', array('path' => $tmpFilePath)); ?>" />
                <?php elseif (!empty($isVideo)): ?>
                    <img src="<?php echo buildUrl('vthumbnail', array('path' => $tmpFilePath)); ?>" />
                <?php else: ?>
                    <?php echo basename($tmpFilePath); ?>
                <?php endif; ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>