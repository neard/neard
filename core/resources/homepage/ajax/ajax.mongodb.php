<?php

$result = array(
    'checkport' => '',
    'versions' => '',
);

// Check port
$port = $neardBins->getMongodb()->getPort();

$textServiceStarted = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED);
$textServiceStopped = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED);
$textDisabled = $neardLang->getValue(Lang::DISABLED);

if ($neardBins->getMongodb()->isEnable()) {
    if ($neardBins->getMongodb()->checkPort($port)) {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-success">' . sprintf($textServiceStarted, $port) . '</span>';
    } else {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-danger">' . $textServiceStopped . '</span>';
    }
} else {
    $result['checkport'] = '<span style="float:right;font-size:12px" class="label label-default">' . $textDisabled . '</span>';
}

// Versions
foreach ($neardBins->getMongodb()->getVersionList() as $version) {
    if ($version != $neardBins->getMongodb()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getMongodb()->getVersion() . '</span>';

echo json_encode($result);
