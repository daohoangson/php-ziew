<?php

require('config.php');
require('helpers.php');
require('functions.php');
@include('vendor/autoload.php');
set_time_limit(0);

switch (getParam('action')) {
    case 'expand_all':
        require('expand_all.php');
        break;
    case 'reset_config':
        require('reset_config.php');
        break;
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
    case 'config':
        require('page_config.php');
        break;
    case 'files':
        require('page_files.php');
        break;
    case 'videos':
        require('page_videos.php');
        break;
    case 'view':
        require('page_view.php');
        break;
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
