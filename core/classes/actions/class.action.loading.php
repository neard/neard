<?php

class ActionLoading
{
    const WINDOW_WIDTH = 340;
    const WINDOW_HEIGHT = 65;
    const GAUGE = 20;
    
    private $wbWindow;
    private $wbProgressBar;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardWinbinder;
        
        $neardWinbinder->reset();
        $neardCore->addLoadingPid(Win32Ps::getCurrentPid());
        
        // Screen infos
        $screenArea = explode(' ', $neardWinbinder->getSystemInfo(WinBinder::SYSINFO_WORKAREA));
        $screenWidth = intval($screenArea[2]);
        $screenHeight = intval($screenArea[3]);
        $xPos = $screenWidth - self::WINDOW_WIDTH;
        $yPos = $screenHeight - self::WINDOW_HEIGHT - 5;
        
        $this->wbWindow = $neardWinbinder->createWindow(null, ToolDialog, null, $xPos, $yPos, self::WINDOW_WIDTH, self::WINDOW_HEIGHT, WBC_TOP, null);
        
        $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::LOADING), 42, 2, 295, null, WBC_LEFT);
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE, 42, 20, 290, 15);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processLoading', 10);
        $neardWinbinder->mainLoop();
    }
    
    public function incrProgressBar($nb = 1)
    {
        global $neardCore, $neardWinbinder;
    
        for ($i = 0; $i < $nb; $i++) {
            $neardWinbinder->incrProgressBar($this->wbProgressBar);
            $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/neard.bmp', 4, 2, 32, 32);
        }
    
        $neardWinbinder->wait();
        $neardWinbinder->wait($this->wbWindow);
    }
    
    public function processLoading($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardWinbinder;
        
        switch ($id) {
            case IDCLOSE:
                Win32Ps::kill(Win32Ps::getCurrentPid());
                break;
        }
        
        while (true) {
            $neardBs->removeErrorHandling();
            $neardWinbinder->resetProgressBar($this->wbProgressBar);
            usleep(100000);
            for ($i = 0; $i < self::GAUGE; $i++) {
                $this->incrProgressBar();
                usleep(100000);
            }
        }
    }
}
