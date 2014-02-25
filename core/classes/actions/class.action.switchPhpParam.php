<?php

class ActionSwitchPhpParam
{
    const SWITCH_ON = 'on';
    const SWITCH_OFF = 'off';
    
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $settingsValues = $neardBins->getPhp()->getSettingsValues();
            if (isset($settingsValues[$args[0]])) {
                $onContent = $args[0] . ' = ' . $settingsValues[$args[0]][0];
                $offContent = $args[0] . ' = ' . $settingsValues[$args[0]][1];
                
                $phpiniContent = file_get_contents($neardBins->getPhp()->getApacheConf());
                if ($args[1] == self::SWITCH_ON) {
                    $phpiniContent = str_replace($offContent, $onContent, $phpiniContent);
                } elseif ($args[1] == self::SWITCH_OFF) {
                    $phpiniContent = str_replace($onContent, $offContent, $phpiniContent);
                }
                
                file_put_contents($neardBins->getPhp()->getApacheConf(), $phpiniContent);
            }
        }
    }

}
