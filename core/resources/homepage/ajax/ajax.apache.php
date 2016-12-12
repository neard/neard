<?php

$result = array(
    'checkport' => '',
    'versions' => '',
    'modulescount' => '',
    'aliasescount' => '',
    'vhostscount' => '',
    'moduleslist' => '',
    'aliaseslist' => '',
    'wwwdirectory' => '',
    'vhostslist' => '',
);

// Check port
$port = $neardBins->getApache()->getPort();
$sslPort = $neardBins->getApache()->getSslPort();

$textServiceStarted = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED);
$textServiceStopped = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED);
$textDisabled = $neardLang->getValue(Lang::DISABLED);

if ($neardBins->getApache()->isEnable()) {
    if ($neardBins->getApache()->checkPort($sslPort, true)) {
        $result['checkport'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-success">' . sprintf($textServiceStarted, $sslPort) . ' (SSL)</span>';
    } else {
        $result['checkport'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-danger">' . $textServiceStopped . ' (SSL)</span>';
    }
    if ($neardBins->getApache()->checkPort($port)) {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-success">' . sprintf($textServiceStarted, $port) . '</span>';
    } else {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-danger">' . $textServiceStopped . '</span>';
    }
} else {
    $result['checkport'] = '<span style="float:right;font-size:12px" class="label label-default">' . $textDisabled . '</span>';
}

// Versions
foreach ($neardBins->getApache()->getVersionList() as $version) {
    if ($version != $neardBins->getApache()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getApache()->getVersion() . '</span>';

// Modules count
$modules = count($neardBins->getApache()->getModules());
$modulesLoaded = count($neardBins->getApache()->getModulesLoaded());
$result['modulescount'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . $modulesLoaded . ' / ' . $modules . '</span>';

// Aliases count
$result['aliasescount'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . count($neardBins->getApache()->getAlias()) . '</span>';

// Vhosts count
$result['vhostscount'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . count($neardBins->getApache()->getVhosts()) . '</span>';

// Modules list
foreach ($neardBins->getApache()->getModulesFromConf() as $moduleName => $moduleStatus) {
    if ($moduleStatus == ActionSwitchApacheModule::SWITCH_ON) {
        $result['moduleslist'] .= '<div class="col-lg-2" style="padding:3px;"><i class="fa fa-check-square-o"></i> <strong>' . $moduleName . '</strong></div>';
    } else {
        $result['moduleslist'] .= '<div class="col-lg-2" style="padding:3px;"><i class="fa fa-square-o"></i> ' . $moduleName . '</div>';
    }
}

// Aliases list
foreach ($neardBins->getApache()->getAlias() as $alias) {
    $result['aliaseslist'] .= '<div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="' . $neardBs->getLocalUrl($alias) . '"><span class="fa fa-link"></span> ' . $alias . '</a></div>';
}

// Www directory
foreach ($neardBins->getApache()->getWwwDirectories() as $wwwDirectory) {
    $result['wwwdirectory'] .= '<div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="' . $neardBs->getLocalUrl($wwwDirectory) . '"><span class="fa fa-link"></span> ' . $wwwDirectory . '</a></div>';
}

// Vhosts list
foreach ($neardBins->getApache()->getVhostsUrl() as $vhost => $enabled) {
    if ($enabled) {
        $result['vhostslist'] .= '<div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="http://' . $vhost . '"><span class="fa fa-check-square-o"></span> ' . $vhost . '</a></div>';
    } else {
        $result['vhostslist'] .= '<div style="float:left;padding:3px;"><a class="btn btn-default" target="_blank" href="http://' . $vhost . '"><span class="fa fa-square-o"></span> ' . $vhost . '</a></div>';
    }
}

echo json_encode($result);
