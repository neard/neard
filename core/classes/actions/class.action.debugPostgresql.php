<?php

class ActionDebugPostgresql
{
    public function __construct($args)
    {
        global $neardLang, $neardBins, $neardTools, $neardWinbinder;
        
        if (isset($args[0]) && !empty($args[0])) {
            $editor = false;
            $msgBoxError = false;
            $caption = $neardLang->getValue(Lang::DEBUG) . ' ' . $neardLang->getValue(Lang::POSTGRESQL) . ' - ';
            if ($args[0] == BinPostgresql::CMD_VERSION) {
                $caption .= $neardLang->getValue(Lang::DEBUG_POSTGRESQL_VERSION);
            }
            $caption .= ' (' . $args[0] . ')';
            
            $debugOutput = $neardBins->getPostgresql()->getCmdLineOutput($args[0]);
            
            if ($editor) {
                Util::openFileContent($caption, $debugOutput);
            } else {
                if ($msgBoxError) {
                    $neardWinbinder->messageBoxError(
                        $debugOutput,
                        $caption
                    );
                } else {
                    $neardWinbinder->messageBoxInfo(
                        $debugOutput,
                        $caption
                    );
                }
            }
        }
    }
}
