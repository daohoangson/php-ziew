<ul class="files">
    <?php foreach ($tmpFilePaths as $tmpFilePath): ?>
        <li class="file">
            <a href="<?php echo buildUrl('view', array('path' => $tmpFilePath)); ?>"
               target="_blank" title="<?php echo basename($tmpFilePath); ?>">
                <?php $thumbnailUrl = getThumbnailUrl($tmpFilePath); ?>
                <?php if (!empty($thumbnailUrl)): ?>
                    <img src="<?php echo $thumbnailUrl; ?>" />
                <?php else: ?>
                    <?php echo basename($tmpFilePath); ?>
                <?php endif; ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>