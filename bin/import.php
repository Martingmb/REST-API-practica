#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php';
if (2 > $argc) {
    echo "Usage: import <env>\n";
    exit(1);
}
$config = array();
$_ENV['SLIM_MODE'] = $argv[1];
$configFile = dirname(__FILE__) . '/share/config/'
    . $_ENV['SLIM_MODE'] . '.php';
if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/../share/config/default.php';
}
// Init database
if (!empty($config['db'])) {
    \ORM::configure($config['db']['dsn']);
    if (!empty($config['db']['username'])
        && !empty($config['db']['password'])) {
        \ORM::configure('username', $config['db']['username']);
        \ORM::configure('password', $config['db']['password']);
    }
}
$db = \ORM::get_db();
$users = file_get_contents(
    dirname(__FILE__) . '/../share/sql/data/users.sql'
);
$contacts = file_get_contents(
    dirname(__FILE__) . '/../share/sql/data/contacts.sql'
);
$db->exec($users);
$db->exec($contacts);
?>