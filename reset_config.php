<?php

setSession('root', '');
setSession('password', '');

header(sprintf('Location: %s', buildUrl('')));