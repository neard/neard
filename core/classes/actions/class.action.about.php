<?php

class ActionAbout
{
    private $wbWindow;
    
    private $wbImage;
    private $wbLinkHomepage;
    private $wbLinkDonate;
    private $wbLinkGithub;
    private $wbBtnOk;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardWinbinder;
        
        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::ABOUT_TITLE), 450, 250, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $aboutText = sprintf($neardLang->getValue(Lang::ABOUT_TEXT), APP_TITLE . ' ' . $neardCore->getAppVersion(), date('Y'), APP_AUTHOR_NAME);
        $neardWinbinder->createLabel($this->wbWindow, $aboutText, 80, 20, 420, 120);
        
        $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::WEBSITE) . ' :', 80, 105, 420, 15);
        $this->wbLinkHomepage = $neardWinbinder->createHyperLink($this->wbWindow, Util::getWebsiteUrlNoUtm(), 180, 105, 250, 15, WBC_LINES);
        
        $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::DONATE) . ' :', 80, 125, 420, 15);
        $this->wbLinkDonate = $neardWinbinder->createHyperLink($this->wbWindow, Util::getWebsiteUrlNoUtm('donate'), 180, 125, 250, 15, WBC_LINES | WBC_RIGHT);
        
        $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::GITHUB) . ' :', 80, 145, 420, 15);
        $this->wbLinkGithub = $neardWinbinder->createHyperLink($this->wbWindow, Util::getGithubUserUrl(), 180, 145, 250, 15, WBC_LINES | WBC_RIGHT);
        
        $this->wbBtnOk = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_OK), 340, 180);
        
        $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/about.bmp');
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardConfig, $neardWinbinder;
        
        switch ($id) {
            case $this->wbLinkHomepage[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getWebsiteUrl());
                break;
            case $this->wbLinkDonate[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getWebsiteUrl('donate'));
                break;
            case $this->wbLinkGithub[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getGithubUserUrl());
                break;
            case IDCLOSE:
            case $this->wbBtnOk[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
