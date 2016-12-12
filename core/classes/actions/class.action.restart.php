<?php

class ActionRestart
{
    public function __construct($args)
    {
        global $neardLang, $neardWinbinder;
        
        $neardWinbinder->messageBoxInfo(
            sprintf($neardLang->getValue(Lang::RESTART_TEXT), APP_TITLE),
            $neardLang->getValue(Lang::RESTART_TITLE));
    }
}
