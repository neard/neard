<?php

class Win32Service
{
    const STOPPED = "1";
    const START_PENDING = "2";
    const STOP_PENDING = "3";
    const RUNNING = "4";
    const CONTINUE_PENDING = "5";
    const PAUSE_PENDING = "6";
    const PAUSED = "7";
    
    const WIN32_ERROR_ACCESS_DENIED = "5";
    const WIN32_ERROR_CIRCULAR_DEPENDENCY = "423";
    const WIN32_ERROR_DATABASE_DOES_NOT_EXIST = "429";
    const WIN32_ERROR_DEPENDENT_SERVICES_RUNNING = "41B";
    const WIN32_ERROR_DUPLICATE_SERVICE_NAME = "436";
    const WIN32_ERROR_FAILED_SERVICE_CONTROLLER_CONNECT = "427"; 
    const WIN32_ERROR_INSUFFICIENT_BUFFER = "7A";
    const WIN32_ERROR_INVALID_DATA = "D";
    const WIN32_ERROR_INVALID_HANDLE = "6";
    const WIN32_ERROR_INVALID_LEVEL = "7C";
    const WIN32_ERROR_INVALID_NAME = "7B";
    const WIN32_ERROR_INVALID_PARAMETER = "57";
    const WIN32_ERROR_INVALID_SERVICE_ACCOUNT = "421";
    const WIN32_ERROR_INVALID_SERVICE_CONTROL = "41C";
    const WIN32_ERROR_PATH_NOT_FOUND = "3";
    const WIN32_ERROR_SERVICE_ALREADY_RUNNING = "420";
    const WIN32_ERROR_SERVICE_CANNOT_ACCEPT_CTRL = "425";
    const WIN32_ERROR_SERVICE_DATABASE_LOCKED = "41F";
    const WIN32_ERROR_SERVICE_DEPENDENCY_DELETED = "433";
    const WIN32_ERROR_SERVICE_DEPENDENCY_FAIL = "42C";
    const WIN32_ERROR_SERVICE_DISABLED = "422";
    const WIN32_ERROR_SERVICE_DOES_NOT_EXIST = "424";
    const WIN32_ERROR_SERVICE_EXISTS = "431";
    const WIN32_ERROR_SERVICE_LOGON_FAILED = "42D";
    const WIN32_ERROR_SERVICE_MARKED_FOR_DELETE = "430";
    const WIN32_ERROR_SERVICE_NO_THREAD = "41E";
    const WIN32_ERROR_SERVICE_NOT_ACTIVE = "426";
    const WIN32_ERROR_SERVICE_REQUEST_TIMEOUT = "41D";
    const WIN32_ERROR_SHUTDOWN_IN_PROGRESS = "45B";
    const WIN32_NO_ERROR = "0";
    
    const SERVER_ERROR_IGNORE = "0";
    const SERVER_ERROR_NORMAL = "1";
    
    const SERVICE_AUTO_START = "2";
    const SERVICE_DEMAND_START = "3";
    const SERVICE_DISABLED = "4";
    
    const PENDING_TIMEOUT = 5;
    const SLEEP_TIME = 500000;
    
    private $name;
    private $displayName;
    private $binPath;
    private $params;
    private $startType;
    private $errorControl;
    private $latestError;
    
    public function __construct($name)
    {
        Util::logInitClass($this);
        
        $this->name = $name;
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getServicesLogFilePath());
    }

    private function callWin32Service($function, $param, $checkError = false)
    {
        $result = false;
        if (function_exists($function)) {
            $result = call_user_func($function, $param);
            if ($checkError && dechex($result) != self::WIN32_NO_ERROR) {
                $this->latestError = dechex($result);
            }
        }
        return $result;
    }

    public function create()
    {
        global $neardBs;
        
        $create = dechex($this->callWin32Service('win32_create_service', array(
            'service' => $this->getName(),
            'display' => $this->getDisplayName(),
            'description' => $this->getDisplayName(),
            'path' => $this->getBinPath(),
            'params' => $this->getParams(),
            'start_type' => $this->getStartType() != null ? $this->getStartType() : self::SERVICE_DEMAND_START,
            'error_control' => $this->getErrorControl() != null ? $this->getErrorControl() : self::SERVER_ERROR_NORMAL,
        ), true));
        
        $this->writeLog('Create service: ' . $create . ' (status: ' . $this->status() . ')');
        $this->writeLog('-> service: ' . $this->getName());
        $this->writeLog('-> display: ' . $this->getDisplayName());
        $this->writeLog('-> description: ' . $this->getDisplayName());
        $this->writeLog('-> path: ' . $this->getBinPath());
        $this->writeLog('-> params: ' . $this->getParams());
        $this->writeLog('-> start_type: ' . ($this->getStartType() != null ? $this->getStartType() : self::SERVICE_DEMAND_START));
        $this->writeLog('-> service: ' . ($this->getErrorControl() != null ? $this->getErrorControl() : self::SERVER_ERROR_NORMAL));
        
        return $this->isInstalled();
    }

    public function delete()
    {
        global $neardBs;
        
        $this->stop();
        $delete = dechex($this->callWin32Service('win32_delete_service', $this->getName(), true));
        $this->writeLog('Delete service ' . $this->getName() . ': ' . $delete . ' (status: ' . $this->status() . ')');
        
        return !$this->isInstalled();
    }

    public function status()
    {
        usleep(self::SLEEP_TIME);
        
        $status = false;
        $maxtime = time() + self::PENDING_TIMEOUT;
        
        while ($status === false || $this->isPending($status)) {
            $status = $this->callWin32Service('win32_query_service_status', $this->getName());
            if (is_array($status) && isset($status['CurrentState'])) {
                $status = dechex($status['CurrentState']);
            } elseif (dechex($status) == self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST) {
                $status = dechex($status);
            }
            /*$this->writeLog('Status ' . $status .
                 PHP_EOL . 'false: ' . ($status === false ? 'YES' : 'NO') .
                 PHP_EOL . 'pending: ' . ($this->isPending($status) ? 'YES' : 'NO') .
                 PHP_EOL . 'maxtime < time: ' . ($maxtime > time() ? 'YES' : 'NO'));*/
            if ($maxtime < time()) {
                break;
            }
        }
        
        return $status;
    }

    public function start()
    {
        global $neardBs;
        $start = dechex($this->callWin32Service('win32_start_service', $this->getName(), true));
        $this->writeLog('Start service ' . $this->getName() . ': ' . $start . ' (status: ' . $this->status() . ')');
        return $this->isRunning();
    }

    public function stop()
    {
        global $neardBs;
        $stop = dechex($this->callWin32Service('win32_stop_service', $this->getName(), true));
        $this->writeLog('Stop service ' . $this->getName() . ': ' . $stop . ' (status: ' . $this->status() . ')');
        return $this->isStopped(); 
    }
    
    public function restart()
    {
        if ($this->stop()) {
            return $this->start();
        }
        return false;
    }
    
    public function isInstalled()
    {
        global $neardBs;
        $status = $this->status();
        $this->writeLog('isInstalled ' . $this->getName() . ': ' . ($status != self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status != self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST;
    }
    
    public function isRunning()
    {
        global $neardBs;
        $status = $this->status();
        $this->writeLog('isRunning ' . $this->getName() . ': ' . ($status == self::RUNNING ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::RUNNING;
    }
    
    public function isStopped()
    {
        global $neardBs;
        $status = $this->status();
        $this->writeLog('isStopped ' . $this->getName() . ': ' . ($status == self::STOPPED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::STOPPED;
    }
    
    public function isPaused()
    {
        global $neardBs;
        $status = $this->status();
        $this->writeLog('isPaused ' . $this->getName() . ': ' . ($status == self::PAUSED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::PAUSED;
    }
    
    public function isPending($status)
    {
        return $status == self::START_PENDING || $status == self::STOP_PENDING
            || $status == self::CONTINUE_PENDING || $status == self::PAUSE_PENDING;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getDisplayName() {
        return $this->displayName;
    }

    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }

    public function getBinPath() {
        return $this->binPath;
    }

    public function setBinPath($binPath) {
        $this->binPath = str_replace('"', '', Util::formatWindowsPath($binPath));
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getStartType() {
        return $this->startType;
    }

    public function setStartType($startType) {
        $this->startType = $startType;
    }

    public function getErrorControl() {
        return $this->errorControl;
    }

    public function setErrorControl($errorControl) {
        $this->errorControl = $errorControl;
    }

    public function getLatestError() {
        return $this->latestError;
    }
    
}
