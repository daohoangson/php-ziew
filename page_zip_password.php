<?php $zipPath = getParam('path'); ?>

<form action="<?php echo buildUrl('save_zip_password', array('path' => $zipPath)); ?>" method="POST">

    <label>
        Password<br />
        <input type="password" name="zip_password" />
    </label><br />

    <input type="submit" value="Save" />

</form>