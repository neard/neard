<?php

class TplAppTools
{
    const MENU = 'tools';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::TOOLS), self::MENU, get_called_class());
    }
    
    public static function getMenuTools()
    {
        global $neardLang, $neardTools;
        
        return TplAestan::getItemConsole(
                $neardLang->getValue(Lang::GIT),
                TplAestan::GLYPH_GIT,
                $neardTools->getConsole()->getTabTitleGit()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::SVN),
                TplAestan::GLYPH_SVN,
                $neardTools->getConsole()->getTabTitleSvn()
            ) . PHP_EOL .
            TplAestan::getItemConsole(
                $neardLang->getValue(Lang::PEAR),
                TplAestan::GLYPH_CONSOLE,
                $neardTools->getConsole()->getTabTitlePear()
            ) . PHP_EOL .
            TplAestan::getItemExe(
                $neardLang->getValue(Lang::XDC),
                $neardTools->getXdc()->getExe(),
                TplAestan::GLYPH_CONSOLE
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::WEBGRIND),
                'http://localhost/webgrind/'
            );
    }
    
}
