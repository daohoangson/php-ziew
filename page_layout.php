<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php getVariable('title'); ?></title>
    <style>
        .size-small { color: green; }
        .size-normal { color: black; }
        .size-big { color: orange; }
        .size-huge { color: red; }
        
        .file img {
            max-height: 100px;
        }

        .files {
            list-style: none;
            margin: 0;
            overflow: auto;
            padding: 0;
            width: 100%
        }

        .files > .file {
            display: block;
            float: left;
            margin: 5px;
        }
    </style>
</head>
<body class="action-<?php echo getParam('action'); ?>">
    <?php echo $contents; ?>
</body>
</html>