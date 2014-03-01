<?php

class ActionChangePort
{
    private $currentPort;
    private $bin;
    private $cntProcessActions;
    
    private $wbWindow;
    
    private $wbLabelCurrent;
    
    private $wbLabelPort;
    private $wbInputPort;
    
    private $wbProgressBar;
    private $wbBtnFinish;
    private $wbBtnCancel;
    
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $this->currentPort = CURRENT_APACHE_PORT;
            $this->bin = $neardBins->getApache();
            $this->cntProcessActions = 5;
            if ($args[0] == $neardBins->getMysql()->getName()) {
                $this->currentPort = CURRENT_MYSQL_PORT;
                $this->bin = $neardBins->getMysql();
                $this->cntProcessActions = 7;
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $this->currentPort = CURRENT_MARIADB_PORT;
                $this->bin = $neardBins->getMariadb();
                $this->cntProcessActions = 5;
            }
            
            $neardWinbinder->reset();
            $this->wbWindow = $neardWinbinder->createAppWindow(sprintf($neardLang->getValue(Lang::CHANGE_PORT_TITLE), $args[0]), 380, 170, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
            
            $this->wbLabelCurrent = $neardWinbinder->createLabel(
                $this->wbWindow,
                sprintf($neardLang->getValue(Lang::CHANGE_PORT_CURRENT_LABEL), $args[0], $this->currentPort), 15, 15, 350);
            
            $this->wbLabelPort = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHANGE_PORT_NEW_LABEL) . ' :', 15, 45, 85, null, WBC_RIGHT);
            $this->wbInputPort = $neardWinbinder->createInputText($this->wbWindow, $this->currentPort, 105, 43, 50, null, 5, WBC_NUMBER);
            
            $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, $this->cntProcessActions + 1, 15, 107, 170);
            $this->wbBtnFinish = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_FINISH), 190, 102);
            $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 277, 102);
            
            $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
            $neardWinbinder->setFocus($this->wbInputPort[WinBinder::CTRL_OBJ]);
            $neardWinbinder->mainLoop();
            $neardWinbinder->reset();
        }
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardBins, $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHANGE_PORT_TITLE), $this->bin);
        $port = $neardWinbinder->getText($this->wbInputPort[WinBinder::CTRL_OBJ]);
    
        switch($id) {
            case $this->wbInputPort[WinBinder::CTRL_ID]:
                $neardWinbinder->setEnabled($this->wbBtnFinish[WinBinder::CTRL_OBJ], empty($port) ? false : true);
                break;
            case $this->wbBtnFinish[WinBinder::CTRL_ID]:
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                if ($port == $this->currentPort) {
                    $neardWinbinder->messageBoxWarning($neardLang->getValue(Lang::CHANGE_PORT_SAME_ERROR), $boxTitle);
                    $neardWinbinder->resetProgressBar($this->wbProgressBar);
                    break;
                }
                if ($this->bin->changePort($port, true, $this->wbProgressBar)) {
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_CHANGED), $this->bin, $port),
                        $boxTitle);
                    $neardWinbinder->destroyWindow($window);
                    
                    Util::startLoading();
                    $this->bin->getService()->restart();
                    
                } else {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port),
                        $boxTitle);
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
