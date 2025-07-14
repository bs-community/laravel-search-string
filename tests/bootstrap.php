<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;

$manager = new Manager();
$manager->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
$manager->bootEloquent();
