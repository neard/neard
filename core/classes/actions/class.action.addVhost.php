<?php

class ActionAddVhost
{
    private $wbWindow;
    
    private $wbLabelServerName;
    private $wbInputServerName;
    
    private $wbLabelDocRoot;
    private $wbInputDocRoot;
    private $wbBtnDocRoot;
    
    private $wbLabelExp;
    
    private $wbProgressBar;
    private $wbBtnSave;
    private $wbBtnCancel;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardBs, $neardLang, $neardWinbinder;
        
        $initServerName = 'test.local';
        $initDocumentRoot = Util::formatWindowsPath($neardBs->getWwwPath()) . '\\' . $initServerName;

        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::ADD_VHOST_TITLE), 490, 200, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $this->wbLabelServerName = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::VHOST_SERVER_NAME_LABEL) . ' :', 15, 15, 85, null, WBC_RIGHT);
        $this->wbInputServerName = $neardWinbinder->createInputText($this->wbWindow, $initServerName, 105, 13, 150, null);
        
        $this->wbLabelDocRoot = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::VHOST_DOCUMENT_ROOT_LABEL) . ' :', 15, 45, 85, null, WBC_RIGHT);
        $this->wbInputDocRoot = $neardWinbinder->createInputText($this->wbWindow, $initDocumentRoot, 105, 43, 190, null, null, WBC_READONLY);
        $this->wbBtnDocRoot = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_BROWSE), 300, 43, 110);
        
        $this->wbLabelExp = $neardWinbinder->createLabel($this->wbWindow, sprintf($neardLang->getValue(Lang::VHOST_EXP_LABEL), $initServerName, $initDocumentRoot), 15, 80, 470, 50);
        
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE_SAVE + 1, 15, 137, 275);
        $this->wbBtnSave = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_SAVE), 300, 132);
        $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 387, 132);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }

    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardBins, $neardLang, $neardOpenSsl, $neardWinbinder;
        
        $serverName = $neardWinbinder->getText($this->wbInputServerName[WinBinder::CTRL_OBJ]);
        $documentRoot = $neardWinbinder->getText($this->wbInputDocRoot[WinBinder::CTRL_OBJ]);
        
        switch ($id) {
            case $this->wbInputServerName[WinBinder::CTRL_ID]:
                $neardWinbinder->setText(
                    $this->wbLabelExp[WinBinder::CTRL_OBJ],
                    sprintf($neardLang->getValue(Lang::VHOST_EXP_LABEL), $serverName, $documentRoot)
                );
                $neardWinbinder->setEnabled($this->wbBtnSave[WinBinder::CTRL_OBJ], empty($serverName) ? false : true);
                break;
            case $this->wbBtnDocRoot[WinBinder::CTRL_ID]:
                $documentRoot = $neardWinbinder->sysDlgPath($window, $neardLang->getValue(Lang::VHOST_DOC_ROOT_PATH), $documentRoot);
                if ($documentRoot && is_dir($documentRoot)) {
                    $neardWinbinder->setText($this->wbInputDocRoot[WinBinder::CTRL_OBJ], $documentRoot . '\\');
                    $neardWinbinder->setText(
                        $this->wbLabelExp[WinBinder::CTRL_OBJ],
                        sprintf($neardLang->getValue(Lang::VHOST_EXP_LABEL), $serverName, $documentRoot . '\\')
                    );
                }
                break;
            case $this->wbBtnSave[WinBinder::CTRL_ID]:
                $neardWinbinder->setProgressBarMax($this->wbProgressBar, self::GAUGE_SAVE + 1);
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                
                if (!Util::isValidDomainName($serverName)) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::VHOST_NOT_VALID_DOMAIN), $serverName),
                        $neardLang->getValue(Lang::ADD_VHOST_TITLE));
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                if (is_file($neardBs->getVhostsPath() . '/' . $serverName . '.conf')) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::VHOST_ALREADY_EXISTS), $serverName),
                        $neardLang->getValue(Lang::ADD_VHOST_TITLE));
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                if ($neardOpenSsl->createCrt($serverName) && file_put_contents($neardBs->getVhostsPath() . '/' . $serverName . '.conf', $neardBins->getApache()->getVhostContent($serverName, $documentRoot)) !== false) {
                    $neardWinbinder->incrProgressBar($this->wbProgressBar);
                    
                    $neardBins->getApache()->getService()->restart();
                    $neardWinbinder->incrProgressBar($this->wbProgressBar);
                    
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::VHOST_CREATED), $serverName, $serverName, $documentRoot),
                        $neardLang->getValue(Lang::ADD_VHOST_TITLE));
                    $neardWinbinder->destroyWindow($window);
                } else {
                    $neardWinbinder->messageBoxError($neardLang->getValue(Lang::VHOST_CREATED_ERROR), $neardLang->getValue(Lang::ADD_VHOST_TITLE));
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                }
                break;
            case IDCLOSE:
            case $this->wbBtnCancel[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
