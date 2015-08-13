<form action="<?php echo buildUrl('save_config'); ?>" method="POST">

    <label>
        ROOT<br />
        <input name="root" />
    </label><br />

    <label>
        Password<br />
        <input type="password" name="password" />
    </label><br />

    <input type="submit" value="Save" />

</form>