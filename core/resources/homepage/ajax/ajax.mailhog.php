<?php

$result = array(
    'checkport' => '',
    'versions' => '',
);

// Check port
$smtpPort = $neardBins->getMailhog()->getSmtpPort();

$textServiceStarted = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STARTED);
$textServiceStopped = $neardLang->getValue(Lang::HOMEPAGE_SERVICE_STOPPED);
$textDisabled = $neardLang->getValue(Lang::DISABLED);

if ($neardBins->getMailhog()->checkPort($smtpPort)) {
    if ($neardBins->getMailhog()->checkPort($smtpPort)) {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-success">' . sprintf($textServiceStarted, $smtpPort) . '</span>';
    } else {
        $result['checkport'] .= '<span style="float:right;font-size:12px" class="label label-danger">' . $textServiceStopped . '</span>';
    }
} else {
    $result['checkport'] = '<span style="float:right;font-size:12px" class="label label-default">' . $textDisabled . '</span>';
}

// Versions
foreach ($neardBins->getMailhog()->getVersionList() as $version) {
    if ($version != $neardBins->getMailhog()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getMailhog()->getVersion() . '</span>';

echo json_encode($result);
