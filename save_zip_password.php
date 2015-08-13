<?php

$zipPath = getParam('path');
if (empty($zipPath)) {
    throw new Exception('$zipPath is missing');
}

if (empty($_POST['zip_password'])) {
    throw new Exception('$_POST["zip_password"] is missing');
}

setZipPassword($zipPath, $_POST['zip_password']);

header(sprintf('Location: %s', buildUrl('zip', array('path' => $zipPath))));