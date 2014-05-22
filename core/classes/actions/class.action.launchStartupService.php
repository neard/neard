<?php

class ActionLaunchStartupService
{
    public function __construct($args)
    {
        global $neardConfig, $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            if ($args[0] == $neardBins->getApache()->getName()) {
                $neardConfig->replace(BinApache::CFG_LAUNCH_STARTUP, $args[1]);
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $neardConfig->replace(BinMysql::CFG_LAUNCH_STARTUP, $args[1]);
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $neardConfig->replace(BinMariadb::CFG_LAUNCH_STARTUP, $args[1]);
            } elseif ($args[0] == $neardBins->getXlight()->getName()) {
                $neardConfig->replace(BinXlight::CFG_LAUNCH_STARTUP, $args[1]);
            }
        }
    }

}
