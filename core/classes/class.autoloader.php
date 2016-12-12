<?php

class Autoloader
{
    public function __construct()
    {
    }

    public function load($class)
    {
        global $neardBs;
        
        $class = strtolower($class);
        $rootPath = $neardBs->getCorePath();
        
        $file = $rootPath . '/classes/class.' . $class . '.php';
        if (Util::startWith($class, 'bin')) {
            $class = $class != 'bins' ? substr_replace($class, '.', 3, 0) : $class;
            $file = $rootPath . '/classes/bins/class.' . $class . '.php';
        } elseif (Util::startWith($class, 'tool')) {
            $class = $class != 'tools' ? substr_replace($class, '.', 4, 0) : $class;
            $file = $rootPath . '/classes/tools/class.' . $class . '.php';
        } elseif (Util::startWith($class, 'app')) {
            $class = $class != 'apps' ? substr_replace($class, '.', 3, 0) : $class;
            $file = $rootPath . '/classes/apps/class.' . $class . '.php';
        } elseif (Util::startWith($class, 'action')) {
            $class = $class != 'action' ? substr_replace($class, '.', 6, 0) : $class;
            $file = $rootPath . '/classes/actions/class.' . $class . '.php';
        } elseif (Util::startWith($class, 'tplapp') && $class != 'tplapp') {
            $class = substr_replace(substr_replace($class, '.', 3, 0), '.', 7, 0);
            $file = $rootPath . '/classes/tpls/app/class.' . $class . '.php';
        } elseif (Util::startWith($class, 'tpl')) {
            $class = $class != 'tpls' ? substr_replace($class, '.', 3, 0) : $class;
            $file = $rootPath . '/classes/tpls/class.' . $class . '.php';
        }
        
        if (!file_exists($file)) {
            return false;
        }
        
        require_once $file;
        return true;
    }
    
    public function register()
    {
        spl_autoload_register(null, false);
        return spl_autoload_register(array($this, 'load'));
    }
    
    public function unregister()
    {
        return spl_autoload_unregister(array($this, 'load'));
    }
}
