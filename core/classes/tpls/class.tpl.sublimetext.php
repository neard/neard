<?php

class TplSublimetext
{
    const HOT_EXIT = 'false';
    const REMEMBER_OPEN_FILES = 'false';
    
    const TAB_SIZE = '4';
    const TRANSLATE_TABS_TO_SPACES = 'true';
    const DETECT_INDENTATION = 'true';
    
    const MENU_VISIBLE = 'false';
    const SHOW_MINIMAP = 'true';
    const SHOW_OPEN_FILES = 'false';
    const SHOW_TABS = 'false';
    const SHOW_FULL_PATH = 'false';
    const SIDE_BAR_VISIBLE = 'false';
    const STATUS_BAR_VISIBLE = 'true';
    
    const WORD_WRAP = 'false';
    
    private function __construct()
    {
        
    }
    
    public static function process()
    {
        global $neardTools;
        
        // Clear Data
        Util::deleteFolder($neardTools->getSublimetext()->getCurrentPath() . '/Data');
        
        // Gen conf
        mkdir(dirname($neardTools->getSublimetext()->getConf()), 0777, true);
        file_put_contents($neardTools->getSublimetext()->getConf(), self::processConf());
        
        // Gen session
        mkdir(dirname($neardTools->getSublimetext()->getSession()), 0777, true);
        file_put_contents($neardTools->getSublimetext()->getSession(), self::processSession());
        
        // Gen placeholder
        file_put_contents($neardTools->getSublimetext()->getCurrentPath() . '/Data/KEEPME', '');
    }
    
    private static function processConf()
    {
        $result = '// Settings in here override those in "Default/Preferences.sublime-settings",' . PHP_EOL;
        $result .= '// and are overridden in turn by file type specific settings.' . PHP_EOL;
        $result .= '{' . PHP_EOL;
        $result .= '  "hot_exit": ' . self::HOT_EXIT . ',' . PHP_EOL;
        $result .= '  "remember_open_files": ' . self::REMEMBER_OPEN_FILES . ',' . PHP_EOL;
        $result .= '  //"trim_trailing_white_space_on_save": true,' . PHP_EOL . PHP_EOL;
        
        $result .= '  "tab_size": ' . self::TAB_SIZE . ',' . PHP_EOL;
        $result .= '  "translate_tabs_to_spaces": ' . self::TRANSLATE_TABS_TO_SPACES . ',' . PHP_EOL;
        $result .= '  "detect_indentation": ' . self::DETECT_INDENTATION . ',' . PHP_EOL . PHP_EOL;
        
        $result .= '  "menu_visible": ' . self::MENU_VISIBLE . ',' . PHP_EOL;
        $result .= '  "show_minimap": ' . self::SHOW_MINIMAP . ',' . PHP_EOL;
        $result .= '  "show_open_files": ' . self::SHOW_OPEN_FILES . ',' . PHP_EOL;
        $result .= '  "show_tabs": ' . self::SHOW_TABS . ',' . PHP_EOL;
        $result .= '  "show_full_path": ' . self::SHOW_FULL_PATH . ',' . PHP_EOL . PHP_EOL;
        $result .= '  "side_bar_visible": ' . self::SIDE_BAR_VISIBLE . ',' . PHP_EOL . PHP_EOL;
        $result .= '  "status_bar_visible": ' . self::STATUS_BAR_VISIBLE . ',' . PHP_EOL . PHP_EOL;
        
        $result .= '  "word_wrap": ' . self::WORD_WRAP . ',' . PHP_EOL;
        $result .= '  //"draw_indent_guides": true,' . PHP_EOL;
        $result .= '  //"draw_white_space": "all",' . PHP_EOL;
        $result .= '}' . PHP_EOL;
        
        return $result;
    }
    
    private static function processSession()
    {
        $result = '{' . PHP_EOL;
        $result .= '    "folder_history":' . PHP_EOL;
        $result .= '    [' . PHP_EOL;
        $result .= '    ],' . PHP_EOL;
        $result .= '    "last_version": 3059,' . PHP_EOL;
        $result .= '    "last_window_id": 1,' . PHP_EOL;
        $result .= '    "log_indexing": false,' . PHP_EOL;
        $result .= '    "settings":' . PHP_EOL;
        $result .= '    {' . PHP_EOL;
        $result .= '        "new_window_height": 480.0,' . PHP_EOL;
        $result .= '        "new_window_settings":' . PHP_EOL;
        $result .= '        {' . PHP_EOL;
        $result .= '            "auto_complete":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "build_system": "",' . PHP_EOL;
        $result .= '            "command_palette":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "console":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "distraction_free":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "menu_visible": ' . self::MENU_VISIBLE . ',' . PHP_EOL;
        $result .= '                "show_minimap": ' . self::SHOW_MINIMAP . ',' . PHP_EOL;
        $result .= '                "show_open_files": ' . self::SHOW_OPEN_FILES . ',' . PHP_EOL;
        $result .= '                "show_tabs": ' . self::SHOW_TABS . ',' . PHP_EOL;
        $result .= '                "side_bar_visible": ' . self::SIDE_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '                "status_bar_visible": ' . self::STATUS_BAR_VISIBLE . '' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "file_history":' . PHP_EOL;
        $result .= '            [' . PHP_EOL;
        $result .= '                "//"' . PHP_EOL;
        $result .= '            ],' . PHP_EOL;
        $result .= '            "find":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "find_in_files":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "where_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "find_state":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "case_sensitive": false,' . PHP_EOL;
        $result .= '                "find_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "highlight": true,' . PHP_EOL;
        $result .= '                "in_selection": false,' . PHP_EOL;
        $result .= '                "preserve_case": false,' . PHP_EOL;
        $result .= '                "regex": false,' . PHP_EOL;
        $result .= '                "replace_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "reverse": false,' . PHP_EOL;
        $result .= '                "show_context": true,' . PHP_EOL;
        $result .= '                "use_buffer2": true,' . PHP_EOL;
        $result .= '                "whole_word": false,' . PHP_EOL;
        $result .= '                "wrap": true' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "incremental_find":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "input":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "menu_visible": ' . self::MENU_VISIBLE . ',' . PHP_EOL;
        $result .= '            "output.find_results":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "replace":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "save_all_on_build": true,' . PHP_EOL;
        $result .= '            "select_file":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "select_project":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "select_symbol":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "show_minimap": ' . self::SHOW_MINIMAP . ',' . PHP_EOL;
        $result .= '            "show_open_files": ' . self::SHOW_OPEN_FILES . ',' . PHP_EOL;
        $result .= '            "show_tabs": ' . self::SHOW_TABS . ',' . PHP_EOL;
        $result .= '            "side_bar_visible": ' . self::SIDE_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '            "side_bar_width": 150.0,' . PHP_EOL;
        $result .= '            "status_bar_visible": ' . self::STATUS_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '            "template_settings":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '            }' . PHP_EOL;
        $result .= '        },' . PHP_EOL;
        $result .= '        "new_window_width": 640.0' . PHP_EOL;
        $result .= '    },' . PHP_EOL;
        $result .= '    "windows":' . PHP_EOL;
        $result .= '    [' . PHP_EOL;
        $result .= '        {' . PHP_EOL;
        $result .= '            "auto_complete":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "build_system": "",' . PHP_EOL;
        $result .= '            "command_palette":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "console":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "distraction_free":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "menu_visible": ' . self::MENU_VISIBLE . ',' . PHP_EOL;
        $result .= '                "show_minimap": ' . self::SHOW_MINIMAP . ',' . PHP_EOL;
        $result .= '                "show_open_files": ' . self::SHOW_OPEN_FILES . ',' . PHP_EOL;
        $result .= '                "show_tabs": ' . self::SHOW_TABS . ',' . PHP_EOL;
        $result .= '                "side_bar_visible": ' . self::SIDE_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '                "status_bar_visible": ' . self::STATUS_BAR_VISIBLE . '' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "file_history":' . PHP_EOL;
        $result .= '            [' . PHP_EOL;
        $result .= '                "//"' . PHP_EOL;
        $result .= '            ],' . PHP_EOL;
        $result .= '            "find":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "find_in_files":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "where_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "find_state":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "case_sensitive": false,' . PHP_EOL;
        $result .= '                "find_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "highlight": true,' . PHP_EOL;
        $result .= '                "in_selection": false,' . PHP_EOL;
        $result .= '                "preserve_case": false,' . PHP_EOL;
        $result .= '                "regex": false,' . PHP_EOL;
        $result .= '                "replace_history":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "reverse": false,' . PHP_EOL;
        $result .= '                "show_context": true,' . PHP_EOL;
        $result .= '                "use_buffer2": true,' . PHP_EOL;
        $result .= '                "whole_word": false,' . PHP_EOL;
        $result .= '                "wrap": true' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "incremental_find":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "input":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "layout":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "cells":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                    [' . PHP_EOL;
        $result .= '                        0,' . PHP_EOL;
        $result .= '                        0,' . PHP_EOL;
        $result .= '                        1,' . PHP_EOL;
        $result .= '                        1' . PHP_EOL;
        $result .= '                    ]' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "cols":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                    0.0,' . PHP_EOL;
        $result .= '                    1.0' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "rows":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                    0.0,' . PHP_EOL;
        $result .= '                    1.0' . PHP_EOL;
        $result .= '                ]' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "menu_visible": ' . self::MENU_VISIBLE . ',' . PHP_EOL;
        $result .= '            "output.find_results":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "position": "0,2,3,-1,-1,-1,-1,763,225,225,881",' . PHP_EOL;
        $result .= '            "replace":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "save_all_on_build": true,' . PHP_EOL;
        $result .= '            "select_file":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "select_project":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "select_symbol":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '                "height": 0.0,' . PHP_EOL;
        $result .= '                "selected_items":' . PHP_EOL;
        $result .= '                [' . PHP_EOL;
        $result .= '                ],' . PHP_EOL;
        $result .= '                "width": 0.0' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "settings":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "show_minimap": ' . self::SHOW_MINIMAP . ',' . PHP_EOL;
        $result .= '            "show_open_files": ' . self::SHOW_OPEN_FILES . ',' . PHP_EOL;
        $result .= '            "show_tabs": ' . self::SHOW_TABS . ',' . PHP_EOL;
        $result .= '            "side_bar_visible": ' . self::SIDE_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '            "side_bar_width": 150.0,' . PHP_EOL;
        $result .= '            "status_bar_visible": ' . self::STATUS_BAR_VISIBLE . ',' . PHP_EOL;
        $result .= '            "template_settings":' . PHP_EOL;
        $result .= '            {' . PHP_EOL;
        $result .= '            },' . PHP_EOL;
        $result .= '            "window_id": 1,' . PHP_EOL;
        $result .= '        }' . PHP_EOL;
        $result .= '    ],' . PHP_EOL;
        $result .= '    "workspaces":' . PHP_EOL;
        $result .= '    {' . PHP_EOL;
        $result .= '        "recent_workspaces":' . PHP_EOL;
        $result .= '        [' . PHP_EOL;
        $result .= '        ]' . PHP_EOL;
        $result .= '    }' . PHP_EOL;
        $result .= '}' . PHP_EOL;
		
		return $result;
    }
}
