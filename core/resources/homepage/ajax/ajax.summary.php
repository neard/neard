<?php

$result = array(
    'binapache' => '',
    'binfilezilla' => '',
    'binmailhog' => '',
    'binmariadb' => '',
    'binmysql' => '',
    'binpostgresql' => '',
    'binmemcached' => '',
    'binnodejs' => '',
    'binphp' => '',
);

$dlMoreTpl = '<a href="' . APP_GITHUB_HOME . '/wiki/%s#latest" target="_blank" title="' . $neardLang->getValue(Lang::DOWNLOAD_MORE) . '"><span style="float:right;margin-left:8px;"><i class="fa fa-download"></i></span></a>';

// Bin Apache
$apachePort = $neardBins->getApache()->getPort();
$apacheSslPort = $neardBins->getApache()->getSslPort();
$apacheLabel = 'label-danger';

if ($neardBins->getApache()->checkPort($apachePort)) {
    if ($neardBins->getApache()->checkPort($apacheSslPort, true)) {
        $apacheLabel = 'label-success';
    } else {
        $apacheLabel = 'label-warning';
    }
}

$result['binapache'] = sprintf($dlMoreTpl, 'binApache');
$result['binapache'] .= '<span style="float:right;font-size:12px" class="label ' . $apacheLabel . '">' . $neardBins->getApache()->getVersion() . '</span>';

// Bin Filezilla
$filezillaPort = $neardBins->getFilezilla()->getPort();
$filezillaSslPort = $neardBins->getFilezilla()->getSslPort();
$filezillaLabel = 'label-danger';

if ($neardBins->getFilezilla()->checkPort($filezillaPort)) {
    if ($neardBins->getFilezilla()->checkPort($filezillaSslPort, true)) {
        $filezillaLabel = 'label-success';
    } else {
        $filezillaLabel = 'label-warning';
    }
}

$result['binfilezilla'] = sprintf($dlMoreTpl, 'binFilezilla');
$result['binfilezilla'] .= '<span style="float:right;font-size:12px" class="label ' . $filezillaLabel . '">' . $neardBins->getFilezilla()->getVersion() . '</span>';

// Bin MailHog
$mailhogPort = $neardBins->getMailhog()->getSmtpPort();
$mailhogLabel = 'label-danger';

if ($neardBins->getMailhog()->checkPort($mailhogPort)) {
    $mailhogLabel = 'label-success';
}

$result['binmailhog'] = sprintf($dlMoreTpl, 'binMailHog');
$result['binmailhog'] .= '<span style="float:right;font-size:12px" class="label ' . $mailhogLabel . '">' . $neardBins->getMailhog()->getVersion() . '</span>';

// Bin MariaDB
$mariadbPort = $neardBins->getMariadb()->getPort();
$mariadbLabel = 'label-danger';

if ($neardBins->getMariadb()->checkPort($mariadbPort)) {
    $mariadbLabel = 'label-success';
}

$result['binmariadb'] = sprintf($dlMoreTpl, 'binMariaDB');
$result['binmariadb'] .= '<span style="float:right;font-size:12px" class="label ' . $mariadbLabel . '">' . $neardBins->getMariadb()->getVersion() . '</span>';

// Bin MySQL
$mysqlPort = $neardBins->getMysql()->getPort();
$mysqlLabel = 'label-danger';

if ($neardBins->getMysql()->checkPort($mysqlPort)) {
    $mysqlLabel = 'label-success';
}

$result['binmysql'] = sprintf($dlMoreTpl, 'binMySQL');
$result['binmysql'] .= '<span style="float:right;font-size:12px" class="label ' . $mysqlLabel . '">' . $neardBins->getMysql()->getVersion() . '</span>';

// Bin PostgreSQL
$postgresqlPort = $neardBins->getPostgresql()->getPort();
$postgresqlLabel = 'label-danger';

if ($neardBins->getPostgresql()->checkPort($postgresqlPort)) {
    $postgresqlLabel = 'label-success';
}

$result['binpostgresql'] = sprintf($dlMoreTpl, 'binPostgreSQL');
$result['binpostgresql'] .= '<span style="float:right;font-size:12px" class="label ' . $postgresqlLabel . '">' . $neardBins->getPostgresql()->getVersion() . '</span>';

// Bin Memcached
$memcachedPort = $neardBins->getMemcached()->getPort();
$memcachedLabel = 'label-danger';

if ($neardBins->getMemcached()->checkPort($memcachedPort)) {
    $memcachedLabel = 'label-success';
}

$result['binmemcached'] = sprintf($dlMoreTpl, 'binMemcached');
$result['binmemcached'] .= '<span style="float:right;font-size:12px" class="label ' . $memcachedLabel . '">' . $neardBins->getMemcached()->getVersion() . '</span>';

// Bin Node.js
$result['binnodejs'] = sprintf($dlMoreTpl, 'binNode.js');
$result['binnodejs'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . $neardBins->getNodejs()->getVersion() . '</span>';

// Bin PHP
$result['binphp'] = sprintf($dlMoreTpl, 'binPHP');
$result['binphp'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . $neardBins->getPhp()->getVersion() . '</span>';

echo json_encode($result);
