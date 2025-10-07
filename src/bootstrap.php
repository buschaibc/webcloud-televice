<?php
use Televice\Support\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';

date_default_timezone_set($config['timezone']);

Container::set('config', $config);
