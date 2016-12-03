<?php

require_once 'util.php';

$defaultEnv = 'prod';

$env = getenv('PHP_ENV') ?: $defaultEnv;

$dir = __DIR__;
$configFile = "$dir/$env/propel.php";

if (!file_exists($configFile)) {
  error_log("no configuration file has been defined for "
          . "environment $env : `$defaultEnv' will be used instead.");
  $env = $defaultEnv;
  $configFile = "$dir/$env/propel.php";
}

$config = include $configFile;

return array_merge_deep($config, [
  'propel' => [
    'paths' => [
      'phpConfDir' => "generated-conf/$env",
      'migrationDir' => "generated-migrations/$env",
      'sqlDir' => "generated-sql/$env",
    ],
  ],
]);

