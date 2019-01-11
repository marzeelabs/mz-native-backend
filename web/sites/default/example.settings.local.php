<?php

/**
 * @file
 * Local development override configuration feature.
 *
 * To activate this feature, copy and rename it such that its path plus
 * filename is 'sites/default/settings.local.php'. Then, go to the bottom of
 * 'sites/default/settings.php' and uncomment the commented lines that mention
 * 'settings.local.php'.
 *
 * If you are using a site name in the path, such as 'sites/example.com', copy
 * this file to 'sites/example.com/settings.local.php', and uncomment the lines
 * at the bottom of 'sites/example.com/settings.php'.
 */

$databases['default']['default'] = array (
  'database' => 'mzdrupal',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = 'SNOWRq-yZbM8pMJE6xkTy86yxRnLyc8Z3noS6Bkf-eF6LQryANaDGrkaPM8VzcLR6ff8ndz_2w';

$settings['install_profile'] = 'minimal';

/* Load development services configuration file */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

/* Disable cache locally */
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

/* Disable JS and CSS aggregation */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;


$config['system.logging']['error_level'] = 'verbose';

// Disable BigPipe for local development.
$settings['big_pipe_override_enabled'] = TRUE;

// Mail to Temp Files
$config['system.mail']['interface']['default'] = 'devel_mail_log';
$config['devel.settings']['debug_mail_directory'] = 'tmp';
