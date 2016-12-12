<?php

class ActionSwitchLang
{
    public function __construct($args)
    {
        global $neardConfig;
        
        if (isset($args[0]) && !empty($args[0])) {
            $neardConfig->replace(Config::CFG_LANG, $args[0]);
        }
    }
}
