<?php

class TplNotepad2
{
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardTools;
        copy($neardTools->getNotepad2()->getConf() . '.nrd', $neardTools->getNotepad2()->getConf());
    }
}
