<?php

class ActionSwitchApacheModule
{
    const SWITCH_ON = 'on';
    const SWITCH_OFF = 'off';
    
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $onContent = 'LoadModule ' . $args[0];
            $offContent = '#LoadModule ' . $args[0];
            
            $httpdContent = file_get_contents($neardBins->getApache()->getConf());
            if ($args[1] == self::SWITCH_ON) {
                $httpdContent = str_replace($offContent, $onContent, $httpdContent);
            } elseif ($args[1] == self::SWITCH_OFF) {
                $httpdContent = str_replace($onContent, $offContent, $httpdContent);
            }
            
            file_put_contents($neardBins->getApache()->getConf(), $httpdContent);
        }
    }
}
