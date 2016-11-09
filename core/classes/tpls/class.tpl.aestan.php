<?php

class TplAestan
{
    const GLYPH_CONSOLE = 0;
    const GLYPH_ADD = 1;
    const GLYPH_FOLDER_OPEN = 2;
    const GLYPH_FOLDER_CLOSE = 3;
    const GLYPH_BROWSER = 5;
    const GLYPH_FILE = 6;
    const GLYPH_SERVICE_REMOVE = 7;
    const GLYPH_SERVICE_INSTALL = 8;
    const GLYPH_START = 9;
    const GLYPH_PAUSE = 10;
    const GLYPH_STOP = 11;
    const GLYPH_RELOAD = 12;
    const GLYPH_CHECK = 13;
    const GLYPH_SERVICE_ALL_RUNNING = 16;
    const GLYPH_SERVICE_SOME_RUNNING = 17;
    const GLYPH_SERVICE_NONE_RUNNING = 18;
    const GLYPH_WARNING = 19;
    const GLYPH_EXIT = 20;
    const GLYPH_ABOUT = 21;
    const GLYPH_SERVICES_RESTART = 22;
    const GLYPH_SERVICES_STOP = 23;
    const GLYPH_SERVICES_START = 24;
    const GLYPH_LIGHT = 25;
    const GLYPH_GIT = 26;
    const GLYPH_SVN = 27;
    const GLYPH_NODEJS = 28;
    const GLYPH_NETWORK = 29;
    const GLYPH_WEB_PAGE = 30;
    const GLYPH_DEBUG = 31;
    const GLYPH_TRASHCAN = 32;
    const GLYPH_UPDATE = 33;
    const GLYPH_RESTART = 34;
    const GLYPH_SSL_CERTIFICATE = 35;
    const GLYPH_RED_LIGHT = 36;
    const GLYPH_COMPOSER = 37;
    const GLYPH_PEAR = 38;
    const GLYPH_HOSTSEDITOR = 39;
    const GLYPH_PHPUNIT = 40;
    const GLYPH_IMAGEMAGICK = 41;
    const GLYPH_NOTEPAD2 = 42;
    const GLYPH_DRUSH = 43;
    const GLYPH_WPCLI = 44;
    const GLYPH_PASSWORD = 45;
    const GLYPH_PHPMETRICS = 46;
    const GLYPH_FILEZILLA = 47;
    const GLYPH_FOLDER_DISABLED = 48;
    const GLYPH_FOLDER_ENABLED = 49;
    const GLYPH_PYTHON = 50;
    const GLYPH_PYTHON_CP = 51;
    
    const SERVICE_START = 'startresume';
    const SERVICE_STOP = 'stop';
    const SERVICE_RESTART = 'restart';
    const SERVICES_CLOSE = 'closeservices';
    
    const IMG_BAR_PICTURE = 'bar.dat';
    const IMG_GLYPH_SPRITES = 'sprites.dat';
    
    public static function getGlyphFlah($lang)
    {
        
    }
    
    public static function getItemSeparator()
    {
        return 'Type: separator';
    }
    
    public static function getItemConsole($caption, $glyph, $id = null, $title = null, $initDir = null, $command = null)
    {
        global $neardTools, $neardLang;
        
        $consoleParams = '';
        if ($id != null) {
            $consoleParams .= ' -t ""' . $id . '""';
        }
        if ($title != null) {
            $consoleParams .= ' -w ""' . $title . '""';
        }
        if ($initDir != null) {
            $consoleParams .= ' -d ""' . $initDir . '""';
        }
        if ($command != null) {
            $consoleParams .= ' -r ""' . $command . '""';
        }
    
        return self::getItemExe(
            $caption,
            $neardTools->getConsole()->getExe(),
            $glyph,
            $consoleParams
        );
    }
    
    public static function getItemLink($caption, $link, $local = false, $glyph = self::GLYPH_WEB_PAGE)
    {
        global $neardBs, $neardConfig;
        
        if ($local) {
            $link = $neardBs->getLocalUrl($link);
        }
        
        return self::getItemExe(
            $caption,
            $neardConfig->getBrowser(),
            $glyph,
            $link
        );
    }
    
    public static function getItemNotepad($caption, $path)
    {
        global $neardConfig;
        
        return self::getItemExe(
            $caption,
            $neardConfig->getNotepad(),
            self::GLYPH_FILE,
            $path
        );
    }
    
    public static function getItemExe($caption, $exe, $glyph, $params = null)
    {
        global $neardConfig;
    
        return 'Type: item; ' .
            'Caption: "' . $caption . '"; ' .
            'Action: run; ' .
            'FileName: "' . $exe . '"; ' .
            (!empty($params) ? 'Parameters: "' . $params . '"; ' : '') .
            'Glyph: ' . $glyph;
    }
    
    public static function getItemExplore($caption, $path)
    {
        global $neardConfig;
    
        return 'Type: item; ' .
            'Caption: "' . $caption . '"; ' .
            'Action: shellexecute; ' .
            'FileName: "' . $path . '"; ' .
            'Glyph: ' . self::GLYPH_FOLDER_OPEN;
    }
    
    private static function getActionService($service, $action, $item = false)
    {
        global $neardLang;
        $result = '';
        
        if ($service != null) {
            $result = 'Action: service; ' .
                'Service: ' . $service . '; ' .
                'ServiceAction: ' . $action;
        } else {
            $result = 'Action: ' . $action;
        }
        
        if ($item) {
            $result = 'Type: item; ' . $result;
            if ( $action == self::SERVICE_START) {
                $result .= '; Caption: "' . $neardLang->getValue(Lang::MENU_START_SERVICE) . '"' .
                    '; Glyph: ' . self::GLYPH_START;
            } elseif ($action == self::SERVICE_STOP) {
                $result .= '; Caption: "' . $neardLang->getValue(Lang::MENU_STOP_SERVICE) . '"' .
                    '; Glyph: ' . self::GLYPH_STOP;
            } elseif ($action == self::SERVICE_RESTART) {
                $result .= '; Caption: "' . $neardLang->getValue(Lang::MENU_RESTART_SERVICE) . '"' .
                    '; Glyph: ' . self::GLYPH_RELOAD;
            }
        } elseif ($action != self::SERVICES_CLOSE) {
            $result .= '; Flags: ignoreerrors waituntilterminated';
        }
        
        return $result;
    }
    
    public static function getActionServiceStart($service)
    {
        return self::getActionService($service, self::SERVICE_START, false);
    }
    
    public static function getItemActionServiceStart($service)
    {
        return self::getActionService($service, self::SERVICE_STOP, true);
    }
    
    public static function getActionServiceStop($service)
    {
        return self::getActionService($service, self::SERVICE_STOP, false);
    }
    
    public static function getItemActionServiceStop($service)
    {
        return self::getActionService($service, self::SERVICE_START, true);
    }
    
    public static function getActionServiceRestart($service)
    {
        return self::getActionService($service, self::SERVICE_RESTART, false);
    }
    
    public static function getItemActionServiceRestart($service)
    {
        return self::getActionService($service, self::SERVICE_RESTART, true);
    }
    
    public static function getActionServicesClose()
    {
        return self::getActionService(null, self::SERVICES_CLOSE, false);
    }
    
    public static function getItemActionServicesClose()
    {
        return self::getActionService(null, self::SERVICES_CLOSE, true);
    }
    
    public static function getSectionMessages()
    {
        global $neardLang;
    
        return '[Messages]' . PHP_EOL .
            'AllRunningHint=' . $neardLang->getValue(Lang::ALL_RUNNING_HINT) . PHP_EOL .
            'SomeRunningHint=' . $neardLang->getValue(Lang::SOME_RUNNING_HINT) . PHP_EOL .
            'NoneRunningHint=' . $neardLang->getValue(Lang::NONE_RUNNING_HINT) . PHP_EOL;
    }
    
    public static function getSectionConfig()
    {
        global $neardCore;
        return '[Config]' . PHP_EOL .
            'ImageList=' . self::IMG_GLYPH_SPRITES . PHP_EOL .
            'ServiceCheckInterval=1' . PHP_EOL .
            'TrayIconAllRunning=' . self::GLYPH_SERVICE_ALL_RUNNING . PHP_EOL .
            'TrayIconSomeRunning=' . self::GLYPH_SERVICE_SOME_RUNNING . PHP_EOL .
            'TrayIconNoneRunning=' . self::GLYPH_SERVICE_NONE_RUNNING . PHP_EOL .
            'ID={' . strtolower(APP_TITLE) . '}' . PHP_EOL .
            'AboutHeader=' . APP_TITLE . PHP_EOL .
            'AboutVersion=Version ' . $neardCore->getAppVersion() . PHP_EOL;
    }
    
    public static function getSectionMenuRightSettings()
    {
        return '[Menu.Right.Settings]' . PHP_EOL .
            'BarVisible=no' . PHP_EOL .
            'SeparatorsAlignment=center' . PHP_EOL .
            'SeparatorsFade=yes' . PHP_EOL .
            'SeparatorsFadeColor=clBtnShadow' . PHP_EOL .
            'SeparatorsFlatLines=yes' . PHP_EOL .
            'SeparatorsGradientEnd=clSilver' . PHP_EOL .
            'SeparatorsGradientStart=clGray' . PHP_EOL .
            'SeparatorsGradientStyle=horizontal' . PHP_EOL .
            'SeparatorsSeparatorStyle=shortline' . PHP_EOL;
    }
    
    public static function getSectionMenuLeftSettings($caption)
    {
        global $neardConfig;
    
        return '[Menu.Left.Settings]' . PHP_EOL .
            'AutoLineReduction=no' . PHP_EOL .
            'BarVisible=yes' . PHP_EOL .
            'BarCaptionAlignment=bottom' . PHP_EOL .
            'BarCaptionCaption=' . $caption . PHP_EOL .
            'BarCaptionDepth=1' . PHP_EOL .
            'BarCaptionDirection=downtoup' . PHP_EOL .
            'BarCaptionFont=Tahoma,14,clWhite' . PHP_EOL .
            'BarCaptionHighlightColor=clNone' . PHP_EOL .
            'BarCaptionOffsetY=0' . PHP_EOL .
            'BarCaptionShadowColor=clNone' . PHP_EOL .
            'BarPictureHorzAlignment=center' . PHP_EOL .
            'BarPictureOffsetX=0' . PHP_EOL .
            'BarPictureOffsetY=0' . PHP_EOL .
            'BarPicturePicture=' . self::IMG_BAR_PICTURE . PHP_EOL .
            'BarPictureTransparent=yes' . PHP_EOL .
            'BarPictureVertAlignment=bottom' . PHP_EOL .
            'BarBorder=clNone' . PHP_EOL .
            'BarGradientEnd=$00984E00' . PHP_EOL .
            'BarGradientStart=$00984E00' . PHP_EOL .
            'BarGradientStyle=horizontal' . PHP_EOL .
            'BarSide=left' . PHP_EOL .
            'BarSpace=0' . PHP_EOL .
            'BarWidth=32' . PHP_EOL .
            'SeparatorsAlignment=center' . PHP_EOL .
            'SeparatorsFade=yes' . PHP_EOL .
            'SeparatorsFadeColor=clBtnShadow' . PHP_EOL .
            'SeparatorsFlatLines=yes' . PHP_EOL .
            'SeparatorsFont=Arial,8,clWhite,bold' . PHP_EOL .
            'SeparatorsGradientEnd=$00FFAA55' . PHP_EOL .
            'SeparatorsGradientStart=$00550000' . PHP_EOL .
            'SeparatorsGradientStyle=horizontal' . PHP_EOL .
            'SeparatorsSeparatorStyle=caption' . PHP_EOL;
    }
}
