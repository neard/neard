<?php

class ActionAbout
{
    private $wbWindow;
    
    private $wbImage;
    private $wbLinkHomepage;
    private $wbLinkDonate;
    private $wbBtnOk;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardBs, $neardCore, $neardLang, $neardBins, $neardWinbinder;
        
        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::ABOUT_TITLE), 520, 260, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $aboutText = sprintf($neardLang->getValue(Lang::ABOUT_TEXT),  APP_TITLE . ' ' . $neardCore->getAppVersion(), APP_AUTHOR_NAME, APP_AUTHOR_EMAIL);
        $neardWinbinder->createLabel($this->wbWindow, $aboutText, 80, 20, 420, 120);
        
        $this->wbLinkHomepage = $neardWinbinder->createHyperLink($this->wbWindow, APP_GITHUB_HOME, 80, 145, 250, 20, WBC_LINES);
        $this->wbLinkDonate = $neardWinbinder->createHyperLink($this->wbWindow, 'Donate', 420, 145, 60, 20, WBC_LINES | WBC_RIGHT);
        $this->wbBtnOk = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_OK), 405, 182);
        
        $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/about.bmp');
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardConfig, $neardBins, $neardLang, $neardWinbinder;
        
        switch($id) {
            case $this->wbLinkHomepage[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), APP_GITHUB_HOME);
                break;
            case $this->wbLinkDonate[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), $neardConfig->getPaypalLink());
                break;
            case IDCLOSE:
            case $this->wbBtnOk[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
