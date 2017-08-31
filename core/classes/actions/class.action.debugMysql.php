<?php

class ActionDebugMysql
{
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardTools, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $editor = false;
            $msgBoxError = false;
            $caption = $neardLang->getValue(Lang::DEBUG) . ' ' . $neardLang->getValue(Lang::MYSQL) . ' - ';
            if ($args[0] == BinMysql::CMD_VERSION) {
                $caption .= $neardLang->getValue(Lang::DEBUG_MYSQL_VERSION);
            } elseif ($args[0] == BinMysql::CMD_VARIABLES) {
                $editor = true;
                $caption .= $neardLang->getValue(Lang::DEBUG_MYSQL_VARIABLES);
            } elseif ($args[0] == BinMysql::CMD_SYNTAX_CHECK) {
                $caption .= $neardLang->getValue(Lang::DEBUG_MYSQL_SYNTAX_CHECK);
            }
            $caption .= ' (' . $args[0] . ')';
            
            $debugOutput = $neardBins->getMysql()->getCmdLineOutput($args[0]);
            
            if ($args[0] == BinMysql::CMD_SYNTAX_CHECK) {
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
