<?php

class ActionManualRestart
{
    public function __construct($args)
    {
        global $neardCore, $neardBins;
        
        Util::startLoading();
        
        foreach ($neardBins->getServices() as $sName => $service) {
            $service->delete();
        }
        
        Win32Ps::killBins(true);
        
        $neardCore->setExec(ActionExec::RESTART);
        Util::stopLoading();
    }
}
