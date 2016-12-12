<?php

class ActionSwitchPhpParam
{
    const SWITCH_ON = 'on';
    const SWITCH_OFF = 'off';
    
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            if (!$neardBins->getPhp()->isSettingExists($args[0])) {
                $neardWinbinder->messageBoxError(
                    sprintf($neardLang->getValue(Lang::SWITCH_PHP_SETTING_NOT_FOUND), $args[0], $neardBins->getPhp()->getVersion()),
                    $neardLang->getValue(Lang::SWITCH_PHP_SETTING_TITLE)
                );
                return;
            }
            
            $settingsValues = $neardBins->getPhp()->getSettingsValues();
            if (isset($settingsValues[$args[0]])) {
                $onContent = $args[0] . ' = ' . $settingsValues[$args[0]][0];
                $offContent = $args[0] . ' = ' . $settingsValues[$args[0]][1];
                
                $phpiniContent = file_get_contents($neardBins->getPhp()->getConf());
                if ($args[1] == self::SWITCH_ON) {
                    $phpiniContent = str_replace($offContent, $onContent, $phpiniContent);
                } elseif ($args[1] == self::SWITCH_OFF) {
                    $phpiniContent = str_replace($onContent, $offContent, $phpiniContent);
                }
                
                file_put_contents($neardBins->getPhp()->getConf(), $phpiniContent);
            }
        }
    }
}
