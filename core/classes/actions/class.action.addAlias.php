<?php

class ActionAddAlias
{
    private $wbWindow;
    
    private $wbLabelName;
    private $wbInputName;
    
    private $wbLabelDest;
    private $wbInputDest;
    private $wbBtnDest;
    
    private $wbLabelExp;
    
    private $wbProgressBar;
    private $wbBtnSave;
    private $wbBtnCancel;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;
        
        $initName = 'test';
        $initDest = 'C:\\';
        $apachePortUri = $neardBins->getApache()->getPort() != 80 ? ':' . $neardBins->getApache()->getPort() : '';

        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::ADD_ALIAS_TITLE), 490, 200, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $this->wbLabelName = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::ALIAS_NAME_LABEL) . ' :', 15, 15, 85, null, WBC_RIGHT);
        $this->wbInputName = $neardWinbinder->createInputText($this->wbWindow, $initName, 105, 13, 150, null);
        
        $this->wbLabelDest = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::ALIAS_DEST_LABEL) . ' :', 15, 45, 85, null, WBC_RIGHT);
        $this->wbInputDest = $neardWinbinder->createInputText($this->wbWindow, $initDest, 105, 43, 190, null, null, WBC_READONLY);
        $this->wbBtnDest = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_BROWSE), 300, 43, 110);
        
        $this->wbLabelExp = $neardWinbinder->createLabel($this->wbWindow, sprintf($neardLang->getValue(Lang::ALIAS_EXP_LABEL), $apachePortUri, $initName, $initDest), 15, 80, 470, 50);
        
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE_SAVE + 1, 15, 137, 275);
        $this->wbBtnSave = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_SAVE), 300, 132);
        $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 387, 132);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }

    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBs, $neardBins, $neardLang, $neardWinbinder;
        
        $apachePortUri = $neardBins->getApache()->getPort() != 80 ? ':' . $neardBins->getApache()->getPort() : '';
        $aliasName = $neardWinbinder->getText($this->wbInputName[WinBinder::CTRL_OBJ]);
        $aliasDest = $neardWinbinder->getText($this->wbInputDest[WinBinder::CTRL_OBJ]);
        
        switch ($id) {
            case $this->wbInputName[WinBinder::CTRL_ID]:
                $neardWinbinder->setText(
                    $this->wbLabelExp[WinBinder::CTRL_OBJ],
                    sprintf($neardLang->getValue(Lang::ALIAS_EXP_LABEL), $apachePortUri, $aliasName, $aliasDest)
                );
                $neardWinbinder->setEnabled($this->wbBtnSave[WinBinder::CTRL_OBJ], empty($aliasName) ? false : true);
                break;
            case $this->wbBtnDest[WinBinder::CTRL_ID]:
                $aliasDest = $neardWinbinder->sysDlgPath($window, $neardLang->getValue(Lang::ALIAS_DEST_PATH), $aliasDest);
                if ($aliasDest && is_dir($aliasDest)) {
                    $neardWinbinder->setText($this->wbInputDest[WinBinder::CTRL_OBJ], $aliasDest . '\\');
                    $neardWinbinder->setText(
                        $this->wbLabelExp[WinBinder::CTRL_OBJ],
                        sprintf($neardLang->getValue(Lang::ALIAS_EXP_LABEL), $apachePortUri, $aliasName, $aliasDest . '\\')
                    );
                }
                break;
            case $this->wbBtnSave[WinBinder::CTRL_ID]:
                $neardWinbinder->setProgressBarMax($this->wbProgressBar, self::GAUGE_SAVE + 1);
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                
                if (!ctype_alnum($aliasName)) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::ALIAS_NOT_VALID_ALPHA), $aliasName),
                        $neardLang->getValue(Lang::ADD_ALIAS_TITLE));
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                if (is_file($neardBs->getAliasPath() . '/' . $aliasName . '.conf')) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::ALIAS_ALREADY_EXISTS), $aliasName),
                        $neardLang->getValue(Lang::ADD_ALIAS_TITLE));
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                if (file_put_contents($neardBs->getAliasPath() . '/' . $aliasName . '.conf', $neardBins->getApache()->getAliasContent($aliasName, $aliasDest)) !== false) {
                    $neardWinbinder->incrProgressBar($this->wbProgressBar);
                    
                    $neardBins->getApache()->getService()->restart();
                    $neardWinbinder->incrProgressBar($this->wbProgressBar);
                    
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::ALIAS_CREATED), $aliasName, $apachePortUri, $aliasName, $aliasDest),
                        $neardLang->getValue(Lang::ADD_ALIAS_TITLE));
                    $neardWinbinder->destroyWindow($window);
                } else {
                    $neardWinbinder->messageBoxError($neardLang->getValue(Lang::ALIAS_CREATED_ERROR), $neardLang->getValue(Lang::ADD_ALIAS_TITLE));
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
