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
        
        $tplGenSslCertificate = TplApp::getActionMulti(
            self::ACTION_GEN_SSL_CERTIFICATE, null,
            array($neardLang->getValue(Lang::MENU_GEN_SSL_CERTIFICATE), TplAestan::GLYPH_SSL_CERTIFICATE),
            false, get_called_class()
        );
        
        return TplAestan::getItemConsole(
                $neardLang->getValue(Lang::COMPOSER),
                TplAestan::GLYPH_COMPOSER,
                $neardTools->getConsole()->getTabTitleComposer()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::GIT),
                TplAestan::GLYPH_GIT,
                $neardTools->getConsole()->getTabTitleGit()
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::HOSTSEDITOR),
                $neardTools->getHostsEditor()->getExe(),
                TplAestan::GLYPH_HOSTSEDITOR
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::PEAR),
                TplAestan::GLYPH_PEAR,
                $neardTools->getConsole()->getTabTitlePear()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::SVN),
                TplAestan::GLYPH_SVN,
                $neardTools->getConsole()->getTabTitleSvn()
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::WEBGRIND),
                'webgrind/',
                true
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::XDC),
                $neardTools->getXdc()->getExe(),
                TplAestan::GLYPH_CONSOLE
            ) . PHP_EOL .
            $tplGenSslCertificate[TplApp::SECTION_CALL] . PHP_EOL .
            $tplGenSslCertificate[TplApp::SECTION_CONTENT];
    }
    
    public static function getActionGenSslCertificate()
    {
        return TplApp::getActionRun(Action::GEN_SSL_CERTIFICATE);
    }
    
}
