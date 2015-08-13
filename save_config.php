<?php

if (empty($_POST['root'])) {
    throw new Exception('$_POST["root"] is missing');
}

if (empty($_POST['password'])) {
    throw new Exception('$_POST["password"] is missing');
}

setSession('root', $_POST['root']);
setSession('password', base64_encode($_POST['password']));

header(sprintf('Location: %s', buildUrl('')));