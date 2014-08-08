<?php

class ActionSwitchStatus
{
    public function __construct($args)
    {
        global $neardConfig, $neardBins;
        
        if (isset($args[0]) && !empty($args[0])) {
            Util::startLoading();
            $putOnline = $args[0] == Config::STATUS_ONLINE;
            
            $this->switchApache($putOnline);
            $this->switchAlias($putOnline);
            $this->switchVhosts($putOnline);
            $this->switchXlight($putOnline);
            $neardConfig->replace(Config::CFG_STATUS, $args[0]);
        }
    }
    
    private function switchApache($putOnline)
    {
        global $neardBs, $neardBins;
        
        $onlineContent = $neardBins->getApache()->getOnlineContent();
        $offlineContent = $neardBins->getApache()->getOfflineContent();
        
        $apacheConf = file_get_contents($neardBins->getApache()->getConf());
        if ($putOnline) {
            $apacheConf = str_replace($offlineContent, $onlineContent, $apacheConf);
        } else {
            $apacheConf = str_replace($onlineContent, $offlineContent, $apacheConf);
        }
        file_put_contents($neardBins->getApache()->getConf(), $apacheConf);
        
        $sslConf = file_get_contents($neardBins->getApache()->getSslConf());
        if ($putOnline) {
            $sslConf = str_replace($offlineContent, $onlineContent, $sslConf);
        } else {
            $sslConf = str_replace($onlineContent, $offlineContent, $sslConf);
        }
        file_put_contents($neardBins->getApache()->getSslConf(), $sslConf);
    }
    
    private function switchAlias($putOnline)
    {
        global $neardBins;
        $neardBins->getApache()->refreshAlias($putOnline);
    }
    
    private function switchVhosts($putOnline)
    {
        global $neardBins;
        $neardBins->getApache()->refreshVhosts($putOnline);
    }
    
    private function switchXlight($putOnline)
    {
        global $neardBins;
        
        $offlineContent = 'AllowedIPList:"127.0.0.1|::1"';
        
        $result = '';
        if ($putOnline) {
            $fileContent = file_get_contents($neardBins->getXlight()->getConfOption());
            $result = str_replace($offlineContent, '', $fileContent);
        } else {
            $fileContent = file($neardBins->getXlight()->getConfOption());
            foreach ($fileContent as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $result .= $row . PHP_EOL;
                }
            }
            $result .= $offlineContent . PHP_EOL;
        }
        
        file_put_contents($neardBins->getXlight()->getConfOption(), $result);
    }

}
