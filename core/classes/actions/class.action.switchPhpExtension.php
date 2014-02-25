<?php

class ActionSwitchPhpExtension
{
    const SWITCH_ON = 'on';
    const SWITCH_OFF = 'off';
    
    public function __construct($args)
    {
        global $neardBins;
        
        if (isset($args[0]) && !empty($args[0]) && isset($args[1]) && !empty($args[1])) {
            $onContent = 'extension=' . $args[0];
            $offContent = ';extension=' . $args[0];
            $extExists = file_exists($neardBins->getPhp()->getExtPath() . '/' . $args[0] . '.dll');
            
            $phpiniContent = file_get_contents($neardBins->getPhp()->getApacheConf());
            if ($args[1] == self::SWITCH_ON) {
                $phpiniContent = str_replace($offContent, $onContent, $phpiniContent);
            } elseif ($args[1] == self::SWITCH_OFF) {
                $phpiniContent = str_replace($onContent, $offContent, $phpiniContent);
            }
            
            $phpiniContentOr = file_get_contents($neardBins->getPhp()->getApacheConf());
            if ($phpiniContent == $phpiniContentOr && $extExists) {
                $extsIni = $neardBins->getPhp()->getExtensionsFromIni();
                $latestExt = (end($extsIni) == '0' ? ';' : '') . 'extension=' . key($extsIni);
                $phpiniContent = str_replace(
                    $latestExt,
                    $latestExt . PHP_EOL . $onContent,
                    $phpiniContent
                );
            }
            
            file_put_contents($neardBins->getPhp()->getApacheConf(), $phpiniContent);
        }
    }

}
