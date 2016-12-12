<?php

class ActionChangeDbRootPwd
{
    private $bin;
    private $cntProcessActions;
    
    private $wbWindow;
    
    private $wbLabelCurrentPwd;
    private $wbInputCurrentPwd;
    
    private $wbLabelNewPwd1;
    private $wbInputNewPwd1;
    
    private $wbLabelNewPwd2;
    private $wbInputNewPwd2;
    
    private $wbProgressBar;
    private $wbBtnFinish;
    private $wbBtnCancel;
    
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $this->bin = $neardBins->getMysql();
            $this->cntProcessActions = 11;
            if ($args[0] == $neardBins->getMariadb()->getName()) {
                $this->bin = $neardBins->getMariadb();
                $this->cntProcessActions = 11;
            } elseif ($args[0] == $neardBins->getPostgresql()->getName()) {
                $this->bin = $neardBins->getPostgresql();
                $this->cntProcessActions = 10;
            }
            
            $neardWinbinder->reset();
            $this->wbWindow = $neardWinbinder->createAppWindow(sprintf($neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_TITLE), $args[0]), 400, 290, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
            
            $this->wbLabelCurrentPwd = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_CURRENTPWD_LABEL), 15, 15, 280);
            $this->wbInputCurrentPwd = $neardWinbinder->createInputText($this->wbWindow, null, 15, 40, 200, null, null, WBC_MASKED);
            
            $this->wbLabelNewPwd1 = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_NEWPWD1_LABEL), 15, 80, 280);
            $this->wbInputNewPwd1 = $neardWinbinder->createInputText($this->wbWindow, null, 15, 105, 200, null, null, WBC_MASKED);
            
            $this->wbLabelNewPwd2 = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_NEWPWD2_LABEL), 15, 145, 280);
            $this->wbInputNewPwd2 = $neardWinbinder->createInputText($this->wbWindow, null, 15, 170, 200, null, null, WBC_MASKED);
            
            $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, $this->cntProcessActions + 1, 15, 227, 190);
            $this->wbBtnFinish = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_FINISH), 210, 222);
            $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 297, 222);
            
            $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
            $neardWinbinder->setFocus($this->wbInputCurrentPwd[WinBinder::CTRL_OBJ]);
            $neardWinbinder->mainLoop();
            $neardWinbinder->reset();
        }
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_TITLE), $this->bin);
        $currentPwd = $neardWinbinder->getText($this->wbInputCurrentPwd[WinBinder::CTRL_OBJ]);
        $newPwd1 = $neardWinbinder->getText($this->wbInputNewPwd1[WinBinder::CTRL_OBJ]);
        $newPwd2 = $neardWinbinder->getText($this->wbInputNewPwd2[WinBinder::CTRL_OBJ]);
    
        switch ($id) {
            case $this->wbBtnFinish[WinBinder::CTRL_ID]:
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                if ($newPwd1 != $newPwd2) {
                    $neardWinbinder->messageBoxWarning($neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_NOTSAME_ERROR), $boxTitle);
                    $neardWinbinder->setText($this->wbInputNewPwd1[WinBinder::CTRL_OBJ], '');
                    $neardWinbinder->setText($this->wbInputNewPwd2[WinBinder::CTRL_OBJ], '');
                    $neardWinbinder->setFocus($this->wbInputNewPwd1[WinBinder::CTRL_OBJ]);
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                $checkRootPwd = $this->bin->checkRootPassword($currentPwd, $this->wbProgressBar);
                if ($checkRootPwd !== true) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_INCORRECT_ERROR), $this->bin->getName(), $checkRootPwd),
                        $boxTitle
                    );
                    $neardWinbinder->setText($this->wbInputCurrentPwd[WinBinder::CTRL_OBJ], '');
                    $neardWinbinder->setFocus($this->wbInputCurrentPwd[WinBinder::CTRL_OBJ]);
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                $changeRootPwd = $this->bin->changeRootPassword($currentPwd, $newPwd1, $this->wbProgressBar);
                if ($changeRootPwd !== true) {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_INCORRECT_ERROR), $this->bin->getName(), $changeRootPwd),
                        $boxTitle
                    );
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                
                $neardWinbinder->messageBoxInfo(
                    $neardLang->getValue(Lang::CHANGE_DB_ROOT_PWD_TEXT),
                    $boxTitle);
                $neardWinbinder->destroyWindow($window);
                break;
            case IDCLOSE:
            case $this->wbBtnCancel[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
