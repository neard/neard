<?php

class ActionCheckVersion
{
    const DISPLAY_OK = 'displayOk';
    
    private $wbWindow;
    
    private $wbImage;
    private $wbLinkChangelog;
    private $wbLinkFull;
    private $wbBtnOk;
    
    private $currentVersion;
    private $latestVersion;
    
    public function __construct($args)
    {
        global $neardCore, $neardLang, $neardWinbinder;
        
        if (!file_exists($neardCore->getExec())) {
            Util::startLoading();
            $this->currentVersion = $neardCore->getAppVersion();
            $this->latestVersion =  Util::getLatestVersion();
            
            if ($this->latestVersion != null && version_compare($this->currentVersion, $this->latestVersion, '<')) {
                $labelFullLink = $neardLang->getValue(Lang::DOWNLOAD) . ' ' . APP_TITLE . ' ' . $this->latestVersion;
                
                $neardWinbinder->reset();
                $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::CHECK_VERSION_TITLE), 480, 170, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
                
                $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHECK_VERSION_AVAILABLE_TEXT), 80, 15, 470, 120);
                
                $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHECK_VERSION_CHANGELOG_TEXT), 80, 40, 470, 20);
                $this->wbLinkChangelog = $neardWinbinder->createHyperLink($this->wbWindow, Util::getChangelogUrl(false), 80, 57, 470, 20, WBC_LINES | WBC_RIGHT);
                
                $this->wbLinkFull = $neardWinbinder->createHyperLink($this->wbWindow, $labelFullLink, 80, 87, 300, 20, WBC_LINES | WBC_RIGHT);
                
                $this->wbBtnOk = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_OK), 380, 103);
                $this->wbImage = $neardWinbinder->drawImage($this->wbWindow, $neardCore->getResourcesPath() . '/about.bmp');
                
                Util::stopLoading();
                $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
                $neardWinbinder->mainLoop();
                $neardWinbinder->reset();
            } elseif (isset($args[0]) && !empty($args[0]) && $args[0] == self::DISPLAY_OK) {
                Util::stopLoading();
                $neardWinbinder->messageBoxInfo(
                    $neardLang->getValue(Lang::CHECK_VERSION_LATEST_TEXT),
                    $neardLang->getValue(Lang::CHECK_VERSION_TITLE));
            }
        }
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardConfig, $neardWinbinder;
    
        switch ($id) {
            case $this->wbLinkChangelog[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getChangelogUrl());
                break;
            case $this->wbLinkFull[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getVersionUrl($this->latestVersion));
                break;
            case IDCLOSE:
            case $this->wbBtnOk[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
