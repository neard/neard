<?php

class ActionLoading
{
    const WINDOW_WIDTH = 300;
    const WINDOW_HEIGHT = 80;
    const GAUGE = 20;
    
    private $wbWindow;
    private $wbProgressBar;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardWinbinder;
        $neardWinbinder->reset();
        $neardCore->setLoadingPid(Util::getPid());
        
        // Screen infos
        $screenArea = explode(' ', $neardWinbinder->getSystemInfo(WinBinder::SYSINFO_WORKAREA));
        $screenWidth = intval($screenArea[2]);
        $screenHeight = intval($screenArea[3]);
        $xPos = $screenWidth - self::WINDOW_WIDTH;
        $yPos = $screenHeight - self::WINDOW_HEIGHT - 5;
        
        $this->wbWindow = $neardWinbinder->createWindow(null, ToolDialog, null, $xPos, $yPos, self::WINDOW_WIDTH, self::WINDOW_HEIGHT, WBC_TOP, null);
        
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE, 0, 25, 295, 15);
        $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::LOADING), 2, 2, 295, null, WBC_LEFT);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processLoading', 10);
        $neardWinbinder->mainLoop();
    }
    
    public function incrProgressBar($nb = 1)
    {
        global $neardWinbinder;
    
        for ($i = 0; $i < $nb; $i++) {
            $neardWinbinder->incrProgressBar($this->wbProgressBar);
        }
    
        $neardWinbinder->wait();
        $neardWinbinder->wait($this->wbWindow);
    }
    
    public function processLoading($window, $id, $ctrl, $param1, $param2)
    {
        global $neardWinbinder;
        
        switch($id) {
            case IDCLOSE:
                $neardWinbinder->destroyWindow($window);
                Util::stopLoading();
                break;
        }
        
        while (true) {
            $neardWinbinder->resetProgressBar($this->wbProgressBar);
            usleep(100000);
            for ($i = 0; $i < self::GAUGE; $i++) {
                $this->incrProgressBar();
                usleep(100000);
            }
        }
    }
}
