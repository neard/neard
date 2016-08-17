<?php

class ActionLaunchStartupService
{
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            if ($args[0] == $neardBins->getApache()->getName()) {
                $neardBins->getApache()->setLaunchStartup($args[1]);
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $neardBins->getMysql()->setLaunchStartup($args[1]);
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $neardBins->getMariadb()->setLaunchStartup($args[1]);
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $neardBins->getFilezilla()->setLaunchStartup($args[1]);
            } elseif ($args[0] == $neardBins->getMailhog()->getName()) {
                $neardBins->getMailhog()->setLaunchStartup($args[1]);
            }
        }
    }

}
