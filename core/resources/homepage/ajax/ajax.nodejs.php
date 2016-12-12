<?php

$result = array(
    'status' => '',
    'versions' => ''
);

// Status
if ($neardBins->getNodejs()->isEnable()) {
    $result['status'] = '<span style="float:right;font-size:12px" class="label label-primary">' . $neardLang->getValue(Lang::ENABLED) . '</span>';
} else {
    $result['status'] = '<span style="float:right;font-size:12px" class="label label-default">' . $neardLang->getValue(Lang::DISABLED) . '</span>';
}

// Versions
foreach ($neardBins->getNodejs()->getVersionList() as $version) {
    if ($version != $neardBins->getNodejs()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getNodejs()->getVersion() . '</span>';

echo json_encode($result);
