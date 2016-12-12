<?php

$result = array(
    'status' => '',
    'versions' => '',
    'extscount' => '',
    'pearversion' => '',
    'extslist' => '',
);

// Status
if ($neardBins->getPhp()->isEnable()) {
    $result['status'] = '<span style="float:right;font-size:12px" class="label label-primary">' . $neardLang->getValue(Lang::ENABLED) . '</span>';
} else {
    $result['status'] = '<span style="float:right;font-size:12px" class="label label-default">' . $neardLang->getValue(Lang::DISABLED) . '</span>';
}

// Versions
foreach ($neardBins->getPhp()->getVersionList() as $version) {
    if ($version != $neardBins->getPhp()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getPhp()->getVersion() . '</span>';

// Extensions count
$exts = count($neardBins->getPhp()->getExtensions());
$extsLoaded = count($neardBins->getPhp()->getExtensionsLoaded());
$result['extscount'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . $extsLoaded . ' / ' . $exts . '</span>';

// PEAR version
$result['pearversion'] .= '<span style="float:right;font-size:12px" class="label label-primary">' . $neardBins->getPhp()->getPearVersion(true) . '</span>';

// Extensions list
foreach ($neardBins->getPhp()->getExtensionsFromConf() as $extName => $extStatus) {
    if ($extStatus == ActionSwitchPhpExtension::SWITCH_ON) {
        $result['extslist'] .= '<div class="col-lg-2" style="padding:3px;"><i class="fa fa-check-square-o"></i> <strong>' . $extName . ' <sup>' . phpversion(substr($extName, 4)) . '</sup></strong></div>';
    } else {
        $result['extslist'] .= '<div class="col-lg-2" style="padding:3px;"><i class="fa fa-square-o"></i> ' . $extName . '</div>';
    }
}

echo json_encode($result);
