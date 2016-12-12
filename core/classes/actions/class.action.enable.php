<?php

class ActionEnable
{
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1])) {
            Util::startLoading();
            if ($args[0] == $neardBins->getApache()->getName()) {
                $neardBins->getApache()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getPhp()->getName()) {
                $neardBins->getPhp()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getMysql()->getName()) {
                $neardBins->getMysql()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $neardBins->getMariadb()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getNodejs()->getName()) {
                $neardBins->getNodejs()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getPostgresql()->getName()) {
                $neardBins->getPostgresql()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $neardBins->getFilezilla()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getMailhog()->getName()) {
                $neardBins->getMailhog()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getMemcached()->getName()) {
                $neardBins->getMemcached()->setEnable($args[1], true);
            } elseif ($args[0] == $neardBins->getSvn()->getName()) {
                $neardBins->getSvn()->setEnable($args[1], true);
            }
        }
    }
}
