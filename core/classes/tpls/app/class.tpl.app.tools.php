<?php

class TplAppTools
{
    const MENU = 'tools';
    
    const ACTION_GEN_SSL_CERTIFICATE = 'genSslCertificate';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::TOOLS), self::MENU, get_called_class());
    }
    
    public static function getMenuTools()
    {
        global $neardLang, $neardTools;
        $resultItems = $resultActions = '';
        
        // Git
        $tplGit = TplAppGit::process();
        $resultItems .= $tplGit[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplGit[TplApp::SECTION_CONTENT] . PHP_EOL;
        
        // Python
        $tplPython = TplAppPython::process();
        $resultItems .= $tplPython[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplPython[TplApp::SECTION_CONTENT] . PHP_EOL;
        
        // Composer
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::COMPOSER),
            TplAestan::GLYPH_COMPOSER,
            $neardTools->getConsole()->getTabTitleComposer()
        ) . PHP_EOL;
        
        // Drush
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::DRUSH),
            TplAestan::GLYPH_DRUSH,
            $neardTools->getConsole()->getTabTitleDrush()
        ) . PHP_EOL;
        
        // HostsEditor
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::HOSTSEDITOR),
            $neardTools->getHostsEditor()->getExe(),
            TplAestan::GLYPH_HOSTSEDITOR
        ) . PHP_EOL;
        
        // ImageMagick
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::IMAGEMAGICK),
            $neardTools->getImageMagick()->getExe(),
            TplAestan::GLYPH_IMAGEMAGICK
        ) . PHP_EOL;
        
        // Notepad2-mod
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::NOTEPAD2MOD),
            $neardTools->getNotepad2Mod()->getExe(),
            TplAestan::GLYPH_NOTEPAD2
        ) . PHP_EOL;
        
        // Pear
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::PEAR),
            TplAestan::GLYPH_PEAR,
            $neardTools->getConsole()->getTabTitlePear()
        ) . PHP_EOL;
        
        // PhpMetrics
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::PHPMETRICS),
            TplAestan::GLYPH_PHPMETRICS,
            $neardTools->getConsole()->getTabTitlePhpMetrics()
        ) . PHP_EOL;
        
        // PHPUnit
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::PHPUNIT),
            TplAestan::GLYPH_PHPUNIT,
            $neardTools->getConsole()->getTabTitlePhpUnit()
        ) . PHP_EOL;
        
        // Ruby
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::RUBY),
            TplAestan::GLYPH_RUBY,
            $neardTools->getConsole()->getTabTitleRuby()
        ) . PHP_EOL;
        
        // WP-CLI
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::WPCLI),
            TplAestan::GLYPH_WPCLI,
            $neardTools->getConsole()->getTabTitleWpCli()
        ) . PHP_EOL;
        
        // XDebugClient
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::XDC),
            $neardTools->getXdc()->getExe(),
            TplAestan::GLYPH_DEBUG
        ) . PHP_EOL;
        
        $resultItems .= TplAestan::getItemSeparator() . PHP_EOL;
        
        // Console
        $resultItems .= TplAestan::getItemConsole(
            $neardLang->getValue(Lang::CONSOLE),
            TplAestan::GLYPH_CONSOLE
        ) . PHP_EOL;
        
        // Generate SSL Certificate
        $tplGenSslCertificate = TplApp::getActionMulti(
            self::ACTION_GEN_SSL_CERTIFICATE, null,
            array($neardLang->getValue(Lang::MENU_GEN_SSL_CERTIFICATE), TplAestan::GLYPH_SSL_CERTIFICATE),
            false, get_called_class()
        );
        $resultItems .= $tplGenSslCertificate[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplGenSslCertificate[TplApp::SECTION_CONTENT];
        
        return $resultItems . PHP_EOL . $resultActions;
    }
    
    public static function getActionGenSslCertificate()
    {
        return TplApp::getActionRun(Action::GEN_SSL_CERTIFICATE);
    }
}
