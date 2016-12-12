<?php

class ActionGenSslCertificate
{
    private $wbWindow;
    
    private $wbLabelName;
    private $wbInputName;
    
    private $wbLabelDest;
    private $wbInputDest;
    private $wbBtnDest;
    
    private $wbProgressBar;
    private $wbBtnSave;
    private $wbBtnCancel;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardBs, $neardLang, $neardWinbinder;
        
        $initServerName = 'test.local';
        $initDocumentRoot = Util::formatWindowsPath($neardBs->getSslPath());

        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::GENSSL_TITLE), 490, 160, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $this->wbLabelName = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::NAME) . ' :', 15, 15, 85, null, WBC_RIGHT);
        $this->wbInputName = $neardWinbinder->createInputText($this->wbWindow, $initServerName, 105, 13, 150, null);
        
        $this->wbLabelDest = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::TARGET) . ' :', 15, 45, 85, null, WBC_RIGHT);
        $this->wbInputDest = $neardWinbinder->createInputText($this->wbWindow, $initDocumentRoot, 105, 43, 190, null, null, WBC_READONLY);
        $this->wbBtnDest = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_BROWSE), 300, 43, 110);
        
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE_SAVE + 1, 15, 97, 275);
        $this->wbBtnSave = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_SAVE), 300, 92);
        $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 387, 92);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }

    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardLang, $neardOpenSsl, $neardWinbinder;
        
        $name = $neardWinbinder->getText($this->wbInputName[WinBinder::CTRL_OBJ]);
        $target = $neardWinbinder->getText($this->wbInputDest[WinBinder::CTRL_OBJ]);
        
        switch ($id) {
            case $this->wbBtnDest[WinBinder::CTRL_ID]:
                $target = $neardWinbinder->sysDlgPath($window, $neardLang->getValue(Lang::GENSSL_PATH), $target);
                if ($target && is_dir($target)) {
                    $neardWinbinder->setText($this->wbInputDest[WinBinder::CTRL_OBJ], $target . '\\');
                }
                break;
            case $this->wbBtnSave[WinBinder::CTRL_ID]:
                $neardWinbinder->setProgressBarMax($this->wbProgressBar, self::GAUGE_SAVE + 1);
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                
                $target = Util::formatUnixPath($target);
                if ($neardOpenSsl->createCrt($name, $target)) {
                    $neardWinbinder->incrProgressBar($this->wbProgressBar);
                    $neardWinbinder->messageBoxInfo(
                            sprintf($neardLang->getValue(Lang::GENSSL_CREATED), $name),
                            $neardLang->getValue(Lang::GENSSL_TITLE));
                    $neardWinbinder->destroyWindow($window);
                } else {
                    $neardWinbinder->messageBoxError($neardLang->getValue(Lang::GENSSL_CREATED_ERROR), $neardLang->getValue(Lang::GENSSL_TITLE));
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
