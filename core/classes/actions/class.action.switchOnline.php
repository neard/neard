<?php

class ActionSwitchOnline
{
    public function __construct($args)
    {
        global $neardConfig, $neardBins;
        
        if (isset($args[0])) {
            Util::startLoading();
            $putOnline = $args[0] == Config::ENABLED;
            
            $this->switchApache($putOnline);
            $this->switchAlias($putOnline);
            $this->switchVhosts($putOnline);
            $this->switchFilezilla($putOnline);
            $neardConfig->replace(Config::CFG_ONLINE, $args[0]);
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
    
    private function switchFilezilla($putOnline)
    {
        global $neardBins;
        
        if ($putOnline) {
            $neardBins->getFilezilla()->setConf(array(
                BinFilezilla::CFG_IP_FILTER_ALLOWED => '*',
                BinFilezilla::CFG_IP_FILTER_DISALLOWED => '',
            ));
        } else {
            $neardBins->getFilezilla()->setConf(array(
                BinFilezilla::CFG_IP_FILTER_ALLOWED => '127.0.0.1 ::1',
                BinFilezilla::CFG_IP_FILTER_DISALLOWED => '*',
            ));
        }
    }

}
