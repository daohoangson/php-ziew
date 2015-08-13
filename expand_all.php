<?php

$root = getParam('root');
$zipPaths = tryCache('findZipPaths', array($root));
$password = getParam('password');

foreach ($zipPaths as $zipPath) {
    try {
        tryCache('extractZip', array($zipPath, $password));
    } catch (Exception $e) {
        // ignore
    }
}

header(sprintf('Location: %s', buildUrl('')));