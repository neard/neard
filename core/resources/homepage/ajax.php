<?php

include_once '../../bootstrap.php';

$procs = array(
    'summary',
    'latestversion',
    'apache',
    'filezilla',
    'mariadb',
    'mysql',
    'nodejs',
    'php',
);

$proc = Util::cleanPostVar('proc');

if (in_array($proc, $procs)) {
    include 'ajax/ajax.' . $proc . '.php';
}
