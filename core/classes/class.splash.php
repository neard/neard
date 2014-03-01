<?php

class Splash
{
    const IMG_BLANK = 'splash-blank.bmp';
    const IMG_STARTING = 'splash-starting.bmp';
    const IMG_APACHE = 'splash-apache.bmp';
    const IMG_MYSQL = 'splash-mysql.bmp';
    const IMG_MARIADB = 'splash-mariadb.bmp';
    const IMG_GIT = 'splash-git.bmp';
    const IMG_SVN = 'splash-svn.bmp';
    const IMG_RESTART = 'splash-restart.bmp';
    const IMG_EXIT = 'splash-exit.bmp';
    
    const WINDOW_WIDTH = 652;
    const WINDOW_HEIGHT = 448;
    
    private $wbWindow;
    private $wbImage;
    private $wbImageVersion;
    private $wbTextFont;
    private $wbTextLoading;
    private $wbProgressBar;
    
    private $currentImg;
    
    public function __construct()
    {
        Util::logInitClass($this);
        
        $this->currentImg = null;
    }
    
    public function init($title, $gauge, $text, $img = self::IMG_BLANK)
    {
        global $neardWinbinder;
        
        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createNakedWindow($title, self::WINDOW_WIDTH, self::WINDOW_HEIGHT, WBC_BORDER);
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, $gauge + 1, 5, 405, 633, 30);
        $this->wbTextFont = $neardWinbinder->createFont("Arial", 9, 6316128, FTA_BOLD);
        
        $this->setImage($img);
        $this->setTextLoading($text);
        $this->incrProgressBar();
    }
    
    public function setImage($img)
    {
        global $neardCore, $neardWinbinder;
    
        $this->currentImg = $neardCore->getResourcesPath() . '/' . $img;
        $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $this->currentImg);
        $this->drawVersion();
    }
    
    private function drawVersion()
    {
        global $neardConfig, $neardCore, $neardWinbinder;
        
        $img = $neardCore->getResourcesPath() . '/release.bmp';
        if (Util::startWith('testing', $neardConfig->getAppVersion())) {
            $img = $neardCore->getResourcesPath() . '/testing.bmp';
        }
        
        $this->wbImageVersion = $neardWinbinder->drawImage(
            $this->wbWindow, $img,
            441, 253,
            191, 26
        );
    }
    
    public function setTextLoading($caption)
    {
        global $neardWinbinder;
    
        $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $this->currentImg);
        $this->drawVersion();
        $neardWinbinder->drawRect($this->wbWindow, 0, 380, self::WINDOW_WIDTH, 65);
        $neardWinbinder->drawLine($this->wbWindow, 0, 380, self::WINDOW_WIDTH, 380, 6316128, 2);
        $this->wbTextLoading = $neardWinbinder->drawText($this->wbWindow, $caption . ' ...', 7, 382, 630, 25, $this->wbTextFont);
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
    
    public function getWbWindow()
    {
        return $this->wbWindow;
    }
    
}
