<?php

class ActionSwitchStatus
{
    public function __construct($args)
    {
        global $neardConfig, $neardBins;
        
        $onlineContent = '    # START switchOnline tag - Do not replace!' . PHP_EOL .
            '    Order Allow,Deny' . PHP_EOL .
            '    Allow from all' . PHP_EOL .
            '    # END switchOnline tag - Do not replace!';
        $offlineContent = '    # START switchOnline tag - Do not replace!' . PHP_EOL .
            '    Order Deny,Allow' . PHP_EOL .
            '    Deny from all' . PHP_EOL .
            '    Allow from 127.0.0.1 ::1 localhost' . PHP_EOL .
            '    # END switchOnline tag - Do not replace!';
        
        if (isset($args[0]) && !empty($args[0])) {
            $httpdContent = file_get_contents($neardBins->getApache()->getConf());
            if ($args[0] == Config::STATUS_ONLINE) {
                $httpdContent = str_replace($offlineContent, $onlineContent, $httpdContent);
            } elseif ($args[0] == Config::STATUS_OFFLINE) {
                $httpdContent = str_replace($onlineContent, $offlineContent, $httpdContent);
            }
            
            $neardConfig->replace(Config::CFG_STATUS, $args[0]);
            file_put_contents($neardBins->getApache()->getConf(), $httpdContent);
        }
    }

}
