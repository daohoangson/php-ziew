<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo getVariable('title'); ?></title>

    <?php if (getParam('action') === 'view'): ?>
    <link href="vendor/videojs/video.js/dist/video-js/video-js.css" rel="stylesheet" type="text/css">
    <script src="vendor/videojs/video.js/dist/video-js/video.js"></script>
    <?php endif; ?>

    <style>
        .size-small { color: green; }
        .size-normal { color: cornflowerblue; }
        .size-big { color: lightpink; }
        .size-huge { color: orange; }
        
        .file img {
            max-height: 100px;
        }

        ul {
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

        .action-index .files {
            height: 110px;
            overflow: hidden;
        }

        .action-view .target {
            display: block;
            margin: 0 auto;
            max-height: 640px;
            max-width: 100%;
        }

        .action-view .nav {
            margin: 0 auto;
            width: <?php echo(7 * 100); ?>px;
        }
        .action-view .nav > div {
            display: inline-block;
            height: 100px;
            vertical-align: top;
            width: auto;
        }
        .action-view .nav .prev,
        .action-view .nav .next {
            width: <?php echo(3 * 95); ?>px;
        }
        .action-view .nav .prev {
            text-align: right;
        }
        .action-view .nav .nav-file {
            background-position: center;
            background-size: cover;
            color: transparent;
            display: inline-block;
            height: 90px;
            opacity: 0.5;
            vertical-align: top;
            width: 90px;
        }
        .action-view .nav .active .nav-file,
        .action-view .nav .nav-file:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="action-<?php echo getParam('action'); ?>">
    <?php echo $contents; ?>
</body>
</html>