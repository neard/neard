<?php

$result = array(
    'display' => false,
    'download' => '',
    'changelog' => '',
);

$neardCurrentVersion = $neardCore->getAppVersion();
$neardLatestVersion = Util::getLatestVersion();

if ($neardLatestVersion != null && version_compare($neardCurrentVersion, $neardLatestVersion, '<')) {
    $result['display'] = true;
    
    $fullVersionUrl = Util::getVersionUrl($neardLatestVersion);
    $result['download'] .= '<a role="button" class="btn btn-success fullversionurl" href="' . $fullVersionUrl . '" target="_blank"><i class="fa fa-download"></i> ';
    $result['download'] .= $neardLang->getValue(Lang::DOWNLOAD) . ' <strong>' . APP_TITLE . ' ' . $neardLatestVersion . '</strong><br />';
    $result['download'] .= '<small>neard-' . $neardLatestVersion . '.7z</small></a>';
    
    $result['changelog'] = Util::getLatestChangelog(true);
}

echo json_encode($result);
