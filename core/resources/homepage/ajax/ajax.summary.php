<?php

$result = array(
    'binapache' => '',
    'binfilezilla' => '',
    'binmariadb' => '',
    'binmysql' => '',
    'binnodejs' => '',
    'binphp' => '',
);

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

$result['binapache'] = '<span style="float:right;font-size:12px" class="label ' . $apacheLabel . '">' . $neardBins->getApache()->getVersion() . '</span>';

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

$result['binfilezilla'] = '<span style="float:right;font-size:12px" class="label ' . $filezillaLabel . '">' . $neardBins->getFilezilla()->getVersion() . '</span>';

// Bin MariaDB
$mariadbPort = $neardBins->getMariadb()->getPort();
$mariadbLabel = 'label-danger';

if ($neardBins->getMariadb()->checkPort($mariadbPort)) {
    $mariadbLabel = 'label-success';
}

$result['binmariadb'] = '<span style="float:right;font-size:12px" class="label ' . $mariadbLabel . '">' . $neardBins->getMariadb()->getVersion() . '</span>';

// Bin MySQL
$mysqlPort = $neardBins->getMysql()->getPort();
$mysqlLabel = 'label-danger';

if ($neardBins->getMysql()->checkPort($mysqlPort)) {
    $mysqlLabel = 'label-success';
}

$result['binmysql'] = '<span style="float:right;font-size:12px" class="label ' . $mysqlLabel . '">' . $neardBins->getMysql()->getVersion() . '</span>';

// Bin Node.js
$result['binnodejs'] = '<span style="float:right;font-size:12px" class="label label-primary">' . $neardBins->getNodejs()->getVersion() . '</span>';

// Bin PHP
$result['binphp'] = '<span style="float:right;font-size:12px" class="label label-primary">' . $neardBins->getPhp()->getVersion() . '</span>';

echo json_encode($result);
