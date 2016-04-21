<?php

$result = array(
    'checkport' => '',
    'versions' => '',
);

// Check port
$port = $neardBins->getFilezilla()->getPort();
$sslPort = $neardBins->getFilezilla()->getSslPort();

$textServiceStarted = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED);
$textServiceStopped = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED);

if ($neardBins->getFilezilla()->checkPort($sslPort, true)) {
    $result['checkport'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-success">' . sprintf($textServiceStarted, $sslPort) . ' (SSL)</span>';
} else {
    $result['checkport'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-danger">' . $textServiceStopped . ' (SSL)</span>';
}
if ($neardBins->getFilezilla()->checkPort($port)) {
    $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-success">' . sprintf($textServiceStarted, $port) . '</span>';
} else {
    $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-danger">' . $textServiceStopped . '</span>';
}

// Versions
foreach ($neardBins->getFilezilla()->getVersionList() as $version) {
    if ($version != $neardBins->getFilezilla()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getFilezilla()->getVersion() . '</span>';

echo json_encode($result);
