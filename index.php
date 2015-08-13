<?php

require('config.php');
require('helpers.php');
require('functions.php');
@include('vendor/autoload.php');

switch (getParam('action')) {
    case 'save_config':
        require('save_config.php');
        die;
    case 'save_zip_password':
        require('save_zip_password.php');
        die;
    case 'stream':
        require('stream.php');
        die;
    case 'thumbnail':
        require('thumbnail.php');
        die;
    case 'vthumbnail':
        require('vthumbnail.php');
        die;
}

ob_start();
switch (getParam('action')) {
    case 'zip':
        require('page_zip.php');
        break;
    default:
        if (!getParam('root') || !getParam('password')) {
            require('page_config.php');
        } else {
            require('page_index.php');
        }
}
$contents = ob_get_clean();

require('page_layout.php');
