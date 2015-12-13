<?php

class Bootstrap
{
    const ERROR_HANDLER = 'errorHandler';
    
    public $path;
    private $procs;
    private $isBootstrap;

    public function __construct($rootPath)
    {
        $this->path = str_replace('\\', '/', rtrim($rootPath, '/\\'));
        $this->isBootstrap = $_SERVER['PHP_SELF'] == 'bootstrap.php';
    }

    public function register()
    {
        // Params
        set_time_limit(0);
        clearstatcache();
        
        // Error log
        $this->initErrorHandling();
        
        // External classes
        require_once $this->getCorePath() . '/classes/class.util.php';
        Util::logSeparator();
        
        // Autoloader
        require_once $this->getCorePath() . '/classes/class.autoloader.php';
        $neardAutoloader = new Autoloader();
        $neardAutoloader->register();
        
        // Load
        self::loadCore();
        self::loadConfig();
        self::loadLang();
        self::loadBins();
        self::loadTools();
        self::loadApps();
        self::loadWinbinder();
        self::loadRegistry();
        self::loadHomepage();
        
        // Init
        if ($this->isBootstrap) {
            $this->procs = Win32Ps::getListProcs();
        }
    }
    
    public function initErrorHandling()
    {
        error_reporting(-1);
        ini_set('error_log', $this->getErrorLogFilePath());
        ini_set('display_errors', '1');
        set_error_handler(array($this, self::ERROR_HANDLER));
    }
    
	public function removeErrorHandling()
    {
        error_reporting(0);
        ini_set('error_log', null);
        ini_set('display_errors', '0');
        restore_error_handler();
    }
    
    public function getProcs()
    {
        return $this->procs;
    }
    
    public function isBootstrap()
    {
        return $this->isBootstrap;
    }
    
    public function getRootPath($aetrayPath = false)
    {
        $path = dirname($this->path);
        return $aetrayPath ? $this->aetrayPath($path) : $path;
    }
    
    private function aetrayPath($path)
    {
        $path = str_replace($this->getRootPath(), '', $path);
        return '%AeTrayMenuPath%' . substr($path, 1, strlen($path));
    }
    
    public function getAliasPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/alias';
    }
    
    public function getAppsPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/apps';
    }
    
    public function getBinPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/bin';
    }

    public function getCorePath($aetrayPath = false)
    {
        return $aetrayPath ? $this->aetrayPath($this->path) : $this->path;
    }

    public function getLogsPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/logs';
    }
    
    public function getSslPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/ssl';
    }
    
    public function getTmpPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/tmp';
    }

    public function getToolsPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/tools';
    }
    
    public function getVhostsPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/vhosts';
    }
    
    public function getWwwPath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/www';
    }
    
    public function getExeFilePath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/neard.exe';
    }
    
    public function getConfigFilePath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/neard.conf';
    }
    
    public function getIniFilePath($aetrayPath = false)
    {
        return $this->getRootPath($aetrayPath) . '/neard.ini';
    }
    
    public function getSslConfPath($aetrayPath = false)
    {
        return $this->getSslPath($aetrayPath) . '/openssl.cnf';
    }

    public function getLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard.log';
    }
    
    public function getErrorLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-error.log';
    }
    
    public function getServicesLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-services.log';
    }
    
    public function getRegistryLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-registry.log';
    }
    
    public function getStartupLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-startup.log';
    }
    
    public function getBatchLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-batch.log';
    }
    
    public function getVbsLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-vbs.log';
    }
    
    public function getWinbinderLogFilePath($aetrayPath = false)
    {
        return $this->getLogsPath($aetrayPath) . '/neard-winbinder.log';
    }
    
    public function getHomepageFilePath($aetrayPath = false)
    {
        return $this->getWwwPath($aetrayPath) . '/index.php';
    }
    
    public function getProcessName()
    {
        return 'neard';
    }
    
    public function getLocalUrl($request = null)
    {
        global $neardBins;
        return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') .
            (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost') .
            ($neardBins->getApache()->getPort() != 80 && !isset($_SERVER['HTTPS']) ? ':' . $neardBins->getApache()->getPort() : '') .
            (!empty($request) ? '/' . $request : '');
    }
    
    public static function loadCore()
    {
        global $neardCore;
        $neardCore = new Core();
    }
    
    public static function loadConfig()
    {
        global $neardConfig;
        $neardConfig = new Config();
    }
    
    public static function loadLang()
    {
        global $neardLang;
        $neardLang = new LangProc();
    }
    
    public static function loadBins()
    {
        global $neardBins;
        $neardBins = new Bins();
    }
    
    public static function loadTools()
    {
        global $neardTools;
        $neardTools = new Tools();
    }
    
    public static function loadApps()
    {
        global $neardApps;
        $neardApps = new Apps();
    }
    
    public static function loadWinbinder()
    {
        global $neardWinbinder;
        if (extension_loaded('winbinder')) {
            $neardWinbinder = new WinBinder();
        }
    }
    
    public static function loadRegistry()
    {
        global $neardRegistry;
        $neardRegistry = new Registry();
    }
    
    public static function loadHomepage()
    {
        global $neardHomepage;
        $neardHomepage = new Homepage();
    }
    
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (error_reporting() === 0) {
            return;
        }
        
        $errfile = Util::formatUnixPath($errfile);
        $errfile = str_replace($this->getRootPath(), '', $errfile);
        
        $errNames = array(
            E_ERROR             => 'E_ERROR',
            E_WARNING           => 'E_WARNING',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
        );
    
        $content = '[' . date('Y-m-d H:i:s', time()) . '] ';
        $content .= $errNames[$errno] . ' ';
        $content .= $errstr . ' in ' .  $errfile;
        $content .= ' on line ' . $errline . PHP_EOL;
        $content .= self::debugStringBacktrace() . PHP_EOL;
    
        file_put_contents($this->getErrorLogFilePath(), $content, FILE_APPEND);
    }
    
    private static function debugStringBacktrace()
    {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();
        
        $trace = preg_replace('/^#0\s+Bootstrap::debugStringBacktrace[^\n]*\n/', '', $trace, 1);
        $trace = preg_replace('/^#1\s+Bootstrap->errorHandler[^\n]*\n/', '', $trace, 1);
        $trace = preg_replace_callback('/^#(\d+)/m', 'debugStringPregReplace', $trace);
        return $trace;
    }
}

function debugStringPregReplace($match)
{
    return '  #' . ($match[1] - 1);
}
