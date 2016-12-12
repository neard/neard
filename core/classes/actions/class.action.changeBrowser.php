<?php

class ActionChangeBrowser
{
    private $wbWindow;
    
    private $wbLabelExp;
    
    private $wbRadioButton;
    private $wbRadioButtonOther;
    private $wbInputBrowse;
    private $wbBtnBrowse;
    
    private $wbProgressBar;
    private $wbBtnSave;
    private $wbBtnCancel;
    
    const GAUGE_SAVE = 2;
    
    public function __construct($args)
    {
        global $neardConfig, $neardLang, $neardWinbinder;
        
        $neardWinbinder->reset();
        $this->wbWindow = $neardWinbinder->createAppWindow($neardLang->getValue(Lang::CHANGE_BROWSER_TITLE), 490, 350, WBC_NOTIFY, WBC_KEYDOWN | WBC_KEYUP);
        
        $this->wbLabelExp = $neardWinbinder->createLabel($this->wbWindow, $neardLang->getValue(Lang::CHANGE_BROWSER_EXP_LABEL), 15, 15, 470, 50);
        
        $currentBrowser = $neardConfig->getBrowser();
        $this->wbRadioButton[] = $neardWinbinder->createRadioButton($this->wbWindow, $currentBrowser, true, 15, 40, 470, 20, true);
        
        $yPos = 70;
        $installedBrowsers = Vbs::getInstalledBrowsers();
        foreach ($installedBrowsers as $installedBrowser) {
            if ($installedBrowser != $currentBrowser) {
                $this->wbRadioButton[] = $neardWinbinder->createRadioButton($this->wbWindow, $installedBrowser, false, 15, $yPos, 470, 20);
                $yPos += 30;
            }
        }
        
        $this->wbRadioButtonOther = $neardWinbinder->createRadioButton($this->wbWindow, $neardLang->getValue(Lang::CHANGE_BROWSER_OTHER_LABEL), false, 15, $yPos, 470, 15);
        
        $this->wbInputBrowse = $neardWinbinder->createInputText($this->wbWindow, null, 30, $yPos + 30, 190, null, 20, WBC_READONLY);
        $this->wbBtnBrowse = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_BROWSE), 225, $yPos + 25, 110);
        $neardWinbinder->setEnabled($this->wbBtnBrowse[WinBinder::CTRL_OBJ], false);
        
        $this->wbProgressBar = $neardWinbinder->createProgressBar($this->wbWindow, self::GAUGE_SAVE, 15, 287, 275);
        $this->wbBtnSave = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_SAVE), 300, 282);
        $this->wbBtnCancel = $neardWinbinder->createButton($this->wbWindow, $neardLang->getValue(Lang::BUTTON_CANCEL), 387, 282);
        $neardWinbinder->setEnabled($this->wbBtnSave[WinBinder::CTRL_OBJ], empty($currentBrowser) ? false : true);
        
        $neardWinbinder->setHandler($this->wbWindow, $this, 'processWindow');
        $neardWinbinder->mainLoop();
        $neardWinbinder->reset();
    }
    
    public function processWindow($window, $id, $ctrl, $param1, $param2)
    {
        global $neardConfig, $neardLang, $neardWinbinder;
        
        // Get other value
        $browserPath = $neardWinbinder->getText($this->wbInputBrowse[WinBinder::CTRL_OBJ]);
        
        // Get value
        $selected = null;
        if ($neardWinbinder->getValue($this->wbRadioButtonOther[WinBinder::CTRL_OBJ]) == 1) {
            $neardWinbinder->setEnabled($this->wbBtnBrowse[WinBinder::CTRL_OBJ], true);
            $selected = $neardWinbinder->getText($this->wbInputBrowse[WinBinder::CTRL_OBJ]);
        } else {
            $neardWinbinder->setEnabled($this->wbBtnBrowse[WinBinder::CTRL_OBJ], false);
        }
        foreach ($this->wbRadioButton as $radioButton) {
            if ($neardWinbinder->getValue($radioButton[WinBinder::CTRL_OBJ]) == 1) {
                $selected = $neardWinbinder->getText($radioButton[WinBinder::CTRL_OBJ]);
                break;
            }
        }
        
        // Enable/disable save button
        $neardWinbinder->setEnabled($this->wbBtnSave[WinBinder::CTRL_OBJ], empty($selected) ? false : true);
        
        switch ($id) {
            case $this->wbBtnBrowse[WinBinder::CTRL_ID]:
                $browserPath = trim($neardWinbinder->sysDlgOpen(
                    $window,
                    $neardLang->getValue(Lang::ALIAS_DEST_PATH),
                    array(array($neardLang->getValue(Lang::EXECUTABLE), '*.exe')),
                    $browserPath
                ));
                if ($browserPath && is_file($browserPath)) {
                    $neardWinbinder->setText($this->wbInputBrowse[WinBinder::CTRL_OBJ], $browserPath);
                }
                break;
            case $this->wbBtnSave[WinBinder::CTRL_ID]:
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                
                $neardWinbinder->incrProgressBar($this->wbProgressBar);
                $neardConfig->replace(Config::CFG_BROWSER, $selected);
                
                $neardWinbinder->messageBoxInfo(
                    sprintf($neardLang->getValue(Lang::CHANGE_BROWSER_OK), $selected),
                    $neardLang->getValue(Lang::CHANGE_BROWSER_TITLE)
                );
                $neardWinbinder->destroyWindow($window);
                
                break;
            case IDCLOSE:
            case $this->wbBtnCancel[WinBinder::CTRL_ID]:
                $neardWinbinder->destroyWindow($window);
                break;
        }
    }
}
