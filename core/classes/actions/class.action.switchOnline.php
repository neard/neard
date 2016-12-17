<?php

class ActionSwitchOnline
{
    public function __construct($args)
    {
        global $neardConfig;
        
        if (isset($args[0]) && $args[0] == Config::ENABLED || $args[0] == Config::DISABLED) {
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
        global $neardBins;
        $neardBins->getApache()->refreshConf($putOnline);
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
