<?php

class Splash
{
    const WINDOW_WIDTH = 440;
    const WINDOW_HEIGHT = 70;
    
    private $wbWindow;
    private $wbImage;
    private $wbTextLoading;
    private $wbProgressBar;
    
    private $currentImg;
    
    public function __construct()
    {
        Util::logInitClass($this);
        
        $this->currentImg = null;
    }
    
    public function init($title, $gauge, $text)
    {
        global $neardCore, $neardWinbinder;
        
        $neardWinbinder->reset();
        
        $screenArea = explode(' ', $neardWinbinder->getSystemInfo(WinBinder::SYSINFO_WORKAREA));
        $screenWidth = intval($screenArea[2]);
        $screenHeight = intval($screenArea[3]);
        $xPos = $screenWidth - self::WINDOW_WIDTH;
        $yPos = $screenHeight - self::WINDOW_HEIGHT - 5;
        
        $this->wbWindow = $neardWinbinder->createWindow(null, ToolDialog, $title, $xPos, $yPos, self::WINDOW_WIDTH, self::WINDOW_HEIGHT, WBC_TOP | WBC_READONLY, null);
        $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/neard.bmp');
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, $gauge + 1, 42, 24, 390, 15);
        
        $this->setTextLoading($text);
        $this->incrProgressBar();
    }
    
    public function setTextLoading($caption)
    {
        global $neardWinbinder;
        
        $neardWinbinder->drawRect($this->wbWindow, 42, 0, self::WINDOW_WIDTH - 42, self::WINDOW_HEIGHT);
        $this->wbTextLoading = $neardWinbinder->drawText($this->wbWindow, $caption . ' ...', 42, 0, self::WINDOW_WIDTH - 44, 25);
    }
    
    public function incrProgressBar($nb = 1)
    {
        global $neardCore, $neardWinbinder;
    
        for ($i = 0; $i < $nb; $i++) {
            $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/neard.bmp', 4, 4, 32, 32);
            $neardWinbinder->incrProgressBar($this->wbProgressBar);
        }
        
        $neardWinbinder->wait();
        $neardWinbinder->wait($this->wbWindow);
    }
    
    public function getWbWindow()
    {
        return $this->wbWindow;
    }
}
