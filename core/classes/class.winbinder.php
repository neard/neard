<?php

class WinBinder
{
    const CTRL_ID = 0;
    const CTRL_OBJ = 1;
    
    const INCR_PROGRESS_BAR = '++';
    const NEW_LINE = '@nl@';
    
    const BOX_INFO = WBC_INFO;
    const BOX_OK = WBC_OK;
    const BOX_OKCANCEL = WBC_OKCANCEL;
    const BOX_QUESTION = WBC_QUESTION;
    const BOX_ERROR = WBC_STOP;
    const BOX_WARNING = WBC_WARNING;
    const BOX_YESNO = WBC_YESNO;
    const BOX_YESNOCANCEL = WBC_YESNOCANCEL;
    
    const CURSOR_ARROW = 'arrow';
    const CURSOR_CROSS = 'cross';
    const CURSOR_FINGER = 'finger';
    const CURSOR_FORBIDDEN = 'forbidden';
    const CURSOR_HELP = 'help';
    const CURSOR_IBEAM = 'ibeam';
    const CURSOR_NONE = null;
    const CURSOR_SIZEALL = 'sizeall';
    const CURSOR_SIZENESW = 'sizenesw';
    const CURSOR_SIZENS = 'sizens';
    const CURSOR_SIZENWSE = 'sizenwse';
    const CURSOR_SIZEWE = 'sizewe';
    const CURSOR_UPARROW = 'uparrow';
    const CURSOR_WAIT = 'wait';
    const CURSOR_WAITARROW = 'waitarrow';
    
    const SYSINFO_SCREENAREA = 'screenarea';
    const SYSINFO_WORKAREA = 'workarea';
    
    private $defaultTitle;
    private $countCtrls;
    
    public $callback;
    public $gauge;
    
    public function __construct()
    {
        global $neardCore;
        Util::logInitClass($this);
        
        $this->defaultTitle = APP_TITLE . ' ' . $neardCore->getAppVersion();
        $this->reset();
    }
    
    private static function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getWinbinderLogFilePath());
    }

    public function reset()
    {
        $this->countCtrls = 1000;
        $this->callback = array();
    }

    private function callWinBinder($function, $params = array(), $removeErrorHandler = false)
    {
        $result = false;
        if (function_exists($function)) {
            if ($removeErrorHandler) {
                $result = @call_user_func_array($function, $params);
            } else {
                $result = call_user_func_array($function, $params);
            }
        }
        return $result;
    }

    public function createWindow($parent, $wclass, $caption, $xPos, $yPos, $width, $height, $style = null, $params = null)
    {
        global $neardCore;
        
        $caption = empty($caption) ? $this->defaultTitle : $this->defaultTitle . ' - ' . $caption;
        $window = $this->callWinBinder('wb_create_window', array($parent, $wclass, $caption, $xPos, $yPos, $width, $height, $style, $params));
        
        // Set window icon
        $this->setImage($window, $neardCore->getResourcesPath() . '/neard.ico');
        
        return $window;
    }

    public function createControl($parent, $ctlClass, $caption, $xPos, $yPos, $width, $height, $style = null, $params = null)
    {
        $this->countCtrls++;
        return array(
            self::CTRL_ID  => $this->countCtrls,
            self::CTRL_OBJ => $this->callWinBinder('wb_create_control', array(
                $parent, $ctlClass, $caption, $xPos, $yPos, $width, $height, $this->countCtrls, $style, $params
            )),
        );
    }

    public function createAppWindow($caption, $width, $height, $style = null, $params = null)
    {
        return $this->createWindow(null, AppWindow, $caption, WBC_CENTER, WBC_CENTER, $width, $height, $style, $params);
    }
    
    public function createNakedWindow($caption, $width, $height, $style = null, $params = null)
    {
        $window = $this->createWindow(null, NakedWindow, $caption, WBC_CENTER, WBC_CENTER, $width, $height, $style, $params);
        $this->setArea($window, $width, $height);
        return $window;
    }
    
    public function destroyWindow($window)
    {
        $this->callWinBinder('wb_destroy_window', array($window), true);
        exit();
    }
    
    public function mainLoop()
    {
        return $this->callWinBinder('wb_main_loop');
    }
    
    public function refresh($wbobject)
    {
        return $this->callWinBinder('wb_refresh', array($wbobject, true));
    }
    
    public function getSystemInfo($info)
    {
        return $this->callWinBinder('wb_get_system_info', array($info));
    }
    
    public function drawImage($wbobject, $path, $xPos = 0, $yPos = 0, $width = 0, $height = 0)
    {
        $image = $this->callWinBinder('wb_load_image', array($path));
        return $this->callWinBinder('wb_draw_image', array($wbobject, $image, $xPos, $yPos, $width, $height));
    }
    
    public function drawText($parent, $caption, $xPos, $yPos, $width = null, $height = null, $font = null)
    {
        $caption = str_replace(self::NEW_LINE, PHP_EOL, $caption);
        $width = $width == null ? 120 : $width;
        $height = $height == null ? 25 : $height;
        return $this->callWinBinder('wb_draw_text', array($parent, $caption, $xPos, $yPos, $width, $height, $font));
    }
    
    public function drawRect($parent, $xPos, $yPos, $width, $height, $color = 15790320, $filled = true)
    {
        return $this->callWinBinder('wb_draw_rect', array($parent, $xPos, $yPos, $width, $height, $color, $filled));
    }
    
    public function drawLine($wbobject, $xStartPos, $yStartPos, $xEndPos, $yEndPos, $color, $height = 1)
    {
        return $this->callWinBinder('wb_draw_line', array($wbobject, $xStartPos, $yStartPos, $xEndPos, $yEndPos, $color, $height));
    }
    
    public function createFont($fontName, $size = null, $color = null, $style = null)
    {
        return $this->callWinBinder('wb_create_font', array($fontName, $size, $color, $style));
    }
    
    public function wait($wbobject = null)
    {
        return $this->callWinBinder('wb_wait', array($wbobject), true);
    }
    
    public function createTimer($wbobject, $wait = 1000)
    {
        $this->countCtrls++;
        return array(
            self::CTRL_ID  => $this->countCtrls,
            self::CTRL_OBJ => $this->callWinBinder('wb_create_timer', array($wbobject, $this->countCtrls, $wait))
        );
    }
    
    public function destroyTimer($wbobject, $timerobject)
    {
        return $this->callWinBinder('wb_destroy_timer', array($wbobject, $timerobject));
    }
    
    public function exec($cmd, $params = null, $silent = false)
    {
        global $neardCore;
        
        if ($silent) {
            $silent = '"' . $neardCore->getScript(Core::SCRIPT_EXEC_SILENT) . '" "' . $cmd . '"';
            $cmd = 'wscript.exe';
            $params = !empty($params) ? $silent . ' "' . $params . '"' : $silent;
        }
        
        $this->writeLog('exec: ' . $cmd . ' ' . $params);
        return $this->callWinBinder('wb_exec', array($cmd, $params));
    }
    
    public function findFile($filename)
    {
        $result = $this->callWinBinder('wb_find_file', array($filename));
        $this->writeLog('findFile ' . $filename . ': ' . $result);
        return $result != $filename ? $result : false;
    }
    
    public function setHandler($wbobject, $classCallback, $methodCallback, $launchTimer = null)
    {
        if ($launchTimer != null) {
            $launchTimer = $this->createTimer($wbobject, $launchTimer);
        }
        
        $this->callback[$wbobject] = array($classCallback, $methodCallback, $launchTimer);
        return $this->callWinBinder('wb_set_handler', array($wbobject, '__winbinderEventHandler'));
    }
    
    public function setImage($wbobject, $path)
    {
        return $this->callWinBinder('wb_set_image', array($wbobject, $path));
    }
    
    public function setMaxLength($wbobject, $length)
    {
        return $this->callWinBinder('wb_send_message', array($wbobject, 0x00c5, $length, 0));
    }
    
    public function setArea($wbobject, $width, $height)
    {
        return $this->callWinBinder('wb_set_area', array($wbobject, WBC_TITLE, 0, 0, $width, $height));
    }
    
    public function getText($wbobject)
    {
        return $this->callWinBinder('wb_get_text', array($wbobject));
    }
    
    public function setText($wbobject, $content)
    {
        $content = str_replace(self::NEW_LINE, PHP_EOL, $content);
        return $this->callWinBinder('wb_set_text', array($wbobject, $content));
    }
    
    public function getValue($wbobject)
    {
        return $this->callWinBinder('wb_get_value', array($wbobject));
    }
    
    public function setValue($wbobject, $content)
    {
        return $this->callWinBinder('wb_set_value', array($wbobject, $content));
    }
    
    public function getFocus()
    {
        return $this->callWinBinder('wb_get_focus');
    }
    
    public function setFocus($wbobject)
    {
        return $this->callWinBinder('wb_set_focus', array($wbobject));
    }
    
    public function setCursor($wbobject, $type = self::CURSOR_ARROW)
    {
        return $this->callWinBinder('wb_set_cursor', array($wbobject, $type));
    }
    
    public function isEnabled($wbobject)
    {
        return $this->callWinBinder('wb_get_enabled', array($wbobject));
    }
    
    public function setEnabled($wbobject, $enabled = true)
    {
        return $this->callWinBinder('wb_set_enabled', array($wbobject, $enabled));
    }
    
    public function setDisabled($wbobject)
    {
        return $this->setEnabled($wbobject, false);
    }
    
    public function setStyle($wbobject, $style)
    {
        return $this->callWinBinder('wb_set_style', array($wbobject, $style));
    }
    
    public function setRange($wbobject, $min, $max)
    {
        return $this->callWinBinder('wb_set_range', array($wbobject, $min, $max));
    }
    
    public function sysDlgPath($parent, $title, $path = null)
    {
        return $this->callWinBinder('wb_sys_dlg_path', array($parent, $title, $path));
    }
    
    public function sysDlgOpen($parent, $title, $filter = null, $path = null)
    {
        return $this->callWinBinder('wb_sys_dlg_open', array($parent, $title, $filter, $path));
    }

    public function createLabel($parent, $caption, $xPos, $yPos, $width = null, $height = null, $style = null, $params = null)
    {
        $caption = str_replace(self::NEW_LINE, PHP_EOL, $caption);
        $width = $width == null ? 120 : $width;
        $height = $height == null ? 25 : $height;
        return $this->createControl($parent, Label, $caption, $xPos, $yPos, $width, $height, $style, $params);
    }

    public function createInputText($parent, $value, $xPos, $yPos, $width = null, $height = null, $maxLength = null, $style = null, $params = null)
    {
        $value = str_replace(self::NEW_LINE, PHP_EOL, $value);
        $width = $width == null ? 120 : $width;
        $height = $height == null ? 25 : $height;
        $inputText = $this->createControl($parent, EditBox, (string) $value, $xPos, $yPos, $width, $height, $style, $params);
        if (is_numeric($maxLength) && $maxLength > 0) {
            $this->setMaxLength($inputText[self::CTRL_OBJ], $maxLength);
        }
        return $inputText;
    }
    
    public function createEditBox($parent, $value, $xPos, $yPos, $width = null, $height = null, $style = null, $params = null)
    {
        $value = str_replace(self::NEW_LINE, PHP_EOL, $value);
        $width = $width == null ? 540 : $width;
        $height = $height == null ? 340 : $height;
        $editBox = $this->createControl($parent, RTFEditBox, (string) $value, $xPos, $yPos, $width, $height, $style, $params);
        return $editBox;
    }
    
    public function createHyperLink($parent, $caption, $xPos, $yPos, $width = null, $height = null, $style = null, $params = null)
    {
        $caption = str_replace(self::NEW_LINE, PHP_EOL, $caption);
        $width = $width == null ? 120 : $width;
        $height = $height == null ? 15 : $height;
        $hyperLink = $this->createControl($parent, HyperLink, (string) $caption, $xPos, $yPos, $width, $height, $style, $params);
        $this->setCursor($hyperLink[self::CTRL_OBJ], self::CURSOR_FINGER);
        return $hyperLink;
    }
    
    public function createRadioButton($parent, $caption, $checked, $xPos, $yPos, $width = null, $height = null, $startGroup = false)
    {
        $caption = str_replace(self::NEW_LINE, PHP_EOL, $caption);
        $width = $width == null ? 120 : $width;
        $height = $height == null ? 25 : $height;
        return $this->createControl($parent, RadioButton, (string) $caption, $xPos, $yPos, $width, $height, $startGroup ? WBC_GROUP : null, $checked ? 1 : 0);
    }

    public function createButton($parent, $caption, $xPos, $yPos, $width = null, $height = null, $style = null, $params = null)
    {
        $width = $width == null ? 80 : $width;
        $height = $height == null ? 25 : $height;
        return $this->createControl($parent, PushButton, $caption, $xPos, $yPos, $width, $height, $style, $params);
    }
    
    public function createProgressBar($parent, $max, $xPos, $yPos, $width = null, $height = null, $style = null, $params = null)
    {
        global $neardLang;
        
        $width = $width == null ? 200 : $width;
        $height = $height == null ? 15 : $height;
        $progressBar = $this->createControl($parent, Gauge, $neardLang->getValue(Lang::LOADING), $xPos, $yPos, $width, $height, $style, $params);
        
        $this->setRange($progressBar[self::CTRL_OBJ], 0, $max);
        $this->gauge[$progressBar[self::CTRL_OBJ]] = 0;
        
        return $progressBar;
    }
    
    public function incrProgressBar($progressBar)
    {
        $this->setProgressBarValue($progressBar, self::INCR_PROGRESS_BAR);
    }
    
    public function resetProgressBar($progressBar)
    {
        $this->setProgressBarValue($progressBar, 0);
    }
    
    public function setProgressBarValue($progressBar, $value)
    {
        if ($progressBar != null && isset($progressBar[self::CTRL_OBJ]) && isset($this->gauge[$progressBar[self::CTRL_OBJ]])) {
            if (strval($value) == self::INCR_PROGRESS_BAR) {
                $value = $this->gauge[$progressBar[self::CTRL_OBJ]] + 1;
            }
            if (is_numeric($value)) {
                $this->gauge[$progressBar[self::CTRL_OBJ]] = $value;
                $this->setValue($progressBar[self::CTRL_OBJ], $value);
            }
        }
    }
    
    public function setProgressBarMax($progressBar, $max)
    {
        $this->setRange($progressBar[self::CTRL_OBJ], 0, $max);
    }
    
    public function messageBox($message, $type, $title = null)
    {
        $message = str_replace(self::NEW_LINE, PHP_EOL, $message);
        $messageBox = $this->callWinBinder('wb_message_box', array(
            null, strlen($message) < 64 ? str_pad($message, 64) : $message, // Pad message to display entire title
            $title == null ? $this->defaultTitle : $this->defaultTitle . ' - ' . $title, $type
        ));
    
        // Set window icon
        //$this->setImage($messageBox, $neardCore->getResourcesPath() . '/neard.ico');
    
        return $messageBox;
    }

    public function messageBoxInfo($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_INFO, $title);
    }

    public function messageBoxOk($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_OK, $title);
    }

    public function messageBoxOkCancel($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_OKCANCEL, $title);
    }

    public function messageBoxQuestion($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_QUESTION, $title);
    }

    public function messageBoxError($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_ERROR, $title);
    }

    public function messageBoxWarning($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_WARNING, $title);
    }

    public function messageBoxYesNo($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_YESNO, $title);
    }

    public function messageBoxYesNoCancel($message, $title = null)
    {
        return $this->messageBox($message, self::BOX_YESNOCANCEL, $title);
    }
}

function __winbinderEventHandler($window, $id, $ctrl, $param1, $param2)
{
    global $neardWinbinder;
    
    if ($neardWinbinder->callback[$window][2] != null) {
        $neardWinbinder->destroyTimer($window, $neardWinbinder->callback[$window][2][0]);
    }
    
    call_user_func_array(
        array($neardWinbinder->callback[$window][0], $neardWinbinder->callback[$window][1]),
        array($window, $id, $ctrl, $param1, $param2)
    );
}
