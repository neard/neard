<?php

class TplAppApps
{
    const MENU = 'apps';
    
    public static function process()
    {
        global $neardLang;
        
        return TplApp::getMenu($neardLang->getValue(Lang::APPS), self::MENU, get_called_class());
    }
    
    public static function getMenuApps()
    {
        global $neardLang;
        
        return TplAestan::getItemLink(
                $neardLang->getValue(Lang::ADMINER),
                'adminer/',
                true
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::GITLIST),
                'gitlist/',
                true
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::PHPMEMADMIN),
                'phpmemadmin/',
                true
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::PHPMYADMIN),
                'phpmyadmin/',
                true
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::PHPPGADMIN),
                'phppgadmin/',
                true
                ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::WEBGRIND),
                'webgrind/',
                true
            ) . PHP_EOL .
            TplAestan::getItemLink(
                $neardLang->getValue(Lang::WEBSVN),
                'websvn/',
                true
            );
    }
}
