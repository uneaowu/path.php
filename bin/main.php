#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Uneaowu\Path\Path;

$p = Path::make('/', 'hello');

dump((string) $p);
