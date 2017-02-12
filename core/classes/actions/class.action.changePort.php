<?php

class ActionChangePort
{
    private $bin;
    private $currentPort;
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
            $this->bin = $neardBins->getApache();
            $this->currentPort = $neardBins->getApache()->getPort();
            $this->cntProcessActions = 3;
            if ($args[0] == $neardBins->getMysql()->getName()) {
                $this->bin = $neardBins->getMysql();
                $this->currentPort = $neardBins->getMysql()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getMariadb()->getName()) {
                $this->bin = $neardBins->getMariadb();
                $this->currentPort = $neardBins->getMariadb()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getMongodb()->getName()) {
                $this->bin = $neardBins->getMongodb();
                $this->currentPort = $neardBins->getMongodb()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getPostgresql()->getName()) {
                $this->bin = $neardBins->getPostgresql();
                $this->currentPort = $neardBins->getPostgresql()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getFilezilla()->getName()) {
                $this->bin = $neardBins->getFilezilla();
                $this->currentPort = $neardBins->getFilezilla()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getMailhog()->getName()) {
                $this->bin = $neardBins->getMailhog();
                $this->currentPort = $neardBins->getMailhog()->getSmtpPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getMemcached()->getName()) {
                $this->bin = $neardBins->getMemcached();
                $this->currentPort = $neardBins->getMemcached()->getPort();
                $this->cntProcessActions = 3;
            } elseif ($args[0] == $neardBins->getSvn()->getName()) {
                $this->bin = $neardBins->getSvn();
                $this->currentPort = $neardBins->getSvn()->getPort();
                $this->cntProcessActions = 3;
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
        global $neardLang, $neardWinbinder;
        $boxTitle = sprintf($neardLang->getValue(Lang::CHANGE_PORT_TITLE), $this->bin);
        $port = $neardWinbinder->getText($this->wbInputPort[WinBinder::CTRL_OBJ]);
    
        switch ($id) {
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
                $changePort = $this->bin->changePort($port, true, $this->wbProgressBar);
                if ($changePort === true) {
                    $this->bin->getService()->restart();
                    
                    $neardWinbinder->messageBoxInfo(
                        sprintf($neardLang->getValue(Lang::PORT_CHANGED), $this->bin, $port),
                        $boxTitle);
                    $neardWinbinder->destroyWindow($window);
                } else {
                    $neardWinbinder->messageBoxError(
                        sprintf($neardLang->getValue(Lang::PORT_NOT_USED_BY), $port, $changePort),
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
