<?php

$result = array(
    'checkport' => '',
    'versions' => '',
);

// Check port
$port = $neardBins->getMysql()->getPort();

$textServiceStarted = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED);
$textServiceStopped = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED);
$textDisabled = $neardLang->getValue(Lang::DISABLED);

if ($neardBins->getMysql()->isEnable()) {
    if ($neardBins->getMysql()->checkPort($port)) {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-success">' . sprintf($textServiceStarted, $port) . '</span>';
    } else {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-danger">' . $textServiceStopped . '</span>';
    }
} else {
    $result['checkport'] = '<span style="float:right;font-size:12px" class="label label-default">' . $textDisabled . '</span>';
}

// Versions
foreach ($neardBins->getMysql()->getVersionList() as $version) {
    if ($version != $neardBins->getMysql()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getMysql()->getVersion() . '</span>';

echo json_encode($result);
