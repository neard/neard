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
        
        $tplGit = TplAppGit::process();
        $tplSvn = TplAppSvn::process();
        
        $tplGenSslCertificate = TplApp::getActionMulti(
            self::ACTION_GEN_SSL_CERTIFICATE, null,
            array($neardLang->getValue(Lang::MENU_GEN_SSL_CERTIFICATE), TplAestan::GLYPH_SSL_CERTIFICATE),
            false, get_called_class()
        );
        
        return $tplGit[TplApp::SECTION_CALL] . PHP_EOL .
            $tplSvn[TplApp::SECTION_CALL] . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::COMPOSER),
                TplAestan::GLYPH_COMPOSER,
                $neardTools->getConsole()->getTabTitleComposer()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::DRUSH),
                TplAestan::GLYPH_DRUSH,
                $neardTools->getConsole()->getTabTitleDrush()
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::HOSTSEDITOR),
                $neardTools->getHostsEditor()->getExe(),
                TplAestan::GLYPH_HOSTSEDITOR
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::IMAGEMAGICK),
                $neardTools->getImageMagick()->getExe(),
                TplAestan::GLYPH_IMAGEMAGICK
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::NOTEPAD2),
                $neardTools->getNotepad2()->getExe(),
                TplAestan::GLYPH_NOTEPAD2
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::PEAR),
                TplAestan::GLYPH_PEAR,
                $neardTools->getConsole()->getTabTitlePear()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::PHPUNIT),
                TplAestan::GLYPH_PHPUNIT,
                $neardTools->getConsole()->getTabTitlePhpUnit()
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::XDC),
                $neardTools->getXdc()->getExe(),
                TplAestan::GLYPH_DEBUG
            ) . PHP_EOL .
            
            TplAestan::getItemSeparator() . PHP_EOL .
            
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::CONSOLE),
                TplAestan::GLYPH_CONSOLE
            ) . PHP_EOL .
            $tplGenSslCertificate[TplApp::SECTION_CALL] . PHP_EOL .
            
            // Actions
            PHP_EOL . $tplGit[TplApp::SECTION_CONTENT] .
            PHP_EOL . $tplSvn[TplApp::SECTION_CONTENT] .
            PHP_EOL . $tplGenSslCertificate[TplApp::SECTION_CONTENT];
    }
    
    public static function getActionGenSslCertificate()
    {
        return TplApp::getActionRun(Action::GEN_SSL_CERTIFICATE);
    }
}
