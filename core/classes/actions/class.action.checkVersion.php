<?php

class ActionCheckVersion
{
    const DISPLAY_OK = 'displayOk';
    
    private $wbWindow;
    
    private $wbImage;
    private $wbLinkPatch;
    private $wbLinkFull;
    private $wbBtnOk;
    
    private $currentVersion;
    private $latestVersion;
    
    public function __construct($args)
    {
        global $neardCore, $neardConfig, $neardLang, $neardWinbinder;
        
        if (!file_exists($neardCore->getExec())) {
            Util::startLoading();
            $this->currentVersion = $neardConfig->getAppVersion();
            $this->latestVersion =  Util::getLatestVersion();
            
            if ($this->latestVersion != null && version_compare($this->currentVersion, $this->latestVersion, '<')) {
                $labelPatchLink = $neardLang->getValue(Lang::DOWNLOAD) . ' Neard ' . $this->currentVersion . '-' . $this->latestVersion . ' Patch';
                $labelPatchInfo = 'neard-' . $this->currentVersion . '-' . $this->latestVersion . '.zip (' . Util::getRemoteFilesize(Util::getPatchUrl($this->currentVersion, $this->latestVersion)) . ')';
                $labelFullLink = $neardLang->getValue(Lang::DOWNLOAD) . ' Neard ' . $this->latestVersion . ' Full';
                $labelFullInfo = 'neard-' . $this->latestVersion . '.zip (' . Util::getRemoteFilesize(Util::getVersionUrl($this->latestVersion)) . ')';
                
                $neardWinbinder->reset();
                $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::CHECK_VERSION_TITLE), 520, 200, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
                
                $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHECK_VERSION_AVAILABLE_TEXT), 80, 20, 420, 120);
    
                $this->wbLinkPatch = $neardWinbinder->createHyperLink($this->wbWindow, $labelPatchLink, 80, 65, 210, 20, WBC_LINES);
                $neardWinbinder->createLabel($this->wbWindow, $labelPatchInfo, 80, 82, 210, 20);
                
                $this->wbLinkFull = $neardWinbinder->createHyperLink($this->wbWindow, $labelFullLink, 300, 65, 210, 20, WBC_LINES | WBC_RIGHT);
                $neardWinbinder->createLabel($this->wbWindow, $labelFullInfo, 300, 82, 210, 20);
                
                $this->wbBtnOk = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_OK), 405, 132);
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
    
        switch($id) {
            case $this->wbLinkPatch[WinBinder::CTRL_ID]:
                $neardWinbinder->exec($neardConfig->getBrowser(), Util::getPatchUrl($this->currentVersion, $this->latestVersion));
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
