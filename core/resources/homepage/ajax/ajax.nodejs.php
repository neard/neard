<?php

$result = array(
    'versions' => '',
);

// Versions
foreach ($neardBins->getNodejs()->getVersionList() as $version) {
    if ($version != $neardBins->getNodejs()->getVersion()) {
        $result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-default">' . $version . '</span>';
    }
}
$result['versions'] .= '<span style="float:right;font-size:12px;margin-left:2px;" class="label label-primary">' . $neardBins->getNodejs()->getVersion() . '</span>';

echo json_encode($result);
