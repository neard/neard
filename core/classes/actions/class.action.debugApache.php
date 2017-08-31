<?php

class ActionDebugApache
{
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardTools, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $editor = false;
            $msgBoxError = false;
            $caption = $neardLang->getValue(Lang::DEBUG) . ' ' . $neardLang->getValue(Lang::APACHE) . ' - ';
            if ($args[0] == BinApache::CMD_VERSION_NUMBER) {
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_VERSION_NUMBER);
            } elseif ($args[0] == BinApache::CMD_COMPILE_SETTINGS) {
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_COMPILE_SETTINGS);
            } elseif ($args[0] == BinApache::CMD_COMPILED_MODULES) {
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_COMPILED_MODULES);
            } elseif ($args[0] == BinApache::CMD_CONFIG_DIRECTIVES) {
                $editor = true;
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_CONFIG_DIRECTIVES);
            } elseif ($args[0] == BinApache::CMD_VHOSTS_SETTINGS) {
                $editor = true;
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_VHOSTS_SETTINGS);
            } elseif ($args[0] == BinApache::CMD_LOADED_MODULES) {
                $editor = true;
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_LOADED_MODULES);
            } elseif ($args[0] == BinApache::CMD_SYNTAX_CHECK) {
                $caption .= $neardLang->getValue(Lang::DEBUG_APACHE_SYNTAX_CHECK);
            }
            $caption .= ' (' . $args[0] . ')';
            
            $debugOutput = $neardBins->getApache()->getCmdLineOutput($args[0]);
            
            if ($args[0] == BinApache::CMD_SYNTAX_CHECK) {
                $msgBoxError = !$debugOutput['syntaxOk'];
                $debugOutput['content'] = $debugOutput['syntaxOk'] ? 'Syntax OK !' : $debugOutput['content'];
            }
            
            if ($editor) {
                Util::openFileContent($caption, $debugOutput['content']);
            } else {
                if ($msgBoxError) {
                    $neardWinbinder->messageBoxError(
                        $debugOutput['content'],
                        $caption
                    );
                } else {
                    $neardWinbinder->messageBoxInfo(
                        $debugOutput['content'],
                        $caption
                    );
                }
            }
        }
    }
}
