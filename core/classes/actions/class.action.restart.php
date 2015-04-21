<?php

class ActionRestart
{
    const GAUGE_RESTART = 6;
    
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardLang, $neardTools, $neardWinbinder;
        
        // Start splash screen
        $splash = new Splash();
        $splash->init(
            $neardLang->getValue(Lang::RESTART),
            self::GAUGE_RESTART,
            sprintf($neardLang->getValue(Lang::STARTUP_RESTARTING_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion())
        );
        
        for($i = 0; $i < self::GAUGE_RESTART; $i++) {
            $splash->incrProgressBar();
            sleep(1);
        }
        
        $splash->incrProgressBar();
        $neardWinbinder->exec($neardTools->getRunFromProcess()->getExe(), 'admin nomsg explorer.exe ' . Util::formatWindowsPath($neardBs->getExeFilePath()), false);
        $neardWinbinder->destroyWindow($splash->getWbWindow());
    }
    
}
