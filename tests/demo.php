<?php
require_once __DIR__ . "/../vendor/autoload.php";

use han8gui\attribution\GeoPhone;

$res = GeoPhone::find("13512345678");

print_r($res);