<?php $_thumbnailUrl = getThumbnailUrl($_tmpFilePath); ?>

<a href="<?php echo buildUrl('view', array('path' => $_tmpFilePath)); ?>"
   title="<?php echo basename($_tmpFilePath); ?>"
    <?php if (!empty($_thumbnailUrl)) echo "style=\"background-image: url($_thumbnailUrl);\""; ?>
   class="nav-file">
    <?php echo basename($_tmpFilePath); ?>
</a>