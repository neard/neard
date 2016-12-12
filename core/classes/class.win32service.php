<?php

class Win32Service
{
    // Win32Service Service Status Constants
    const WIN32_SERVICE_CONTINUE_PENDING = "5";
    const WIN32_SERVICE_PAUSE_PENDING = "6";
    const WIN32_SERVICE_PAUSED = "7";
    const WIN32_SERVICE_RUNNING = "4";
    const WIN32_SERVICE_START_PENDING = "2";
    const WIN32_SERVICE_STOP_PENDING = "3";
    const WIN32_SERVICE_STOPPED = "1";
    const WIN32_SERVICE_NA = "0";
    
    // Win32 Error Codes
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
    
    const PENDING_TIMEOUT = 10;
    const SLEEP_TIME = 500000;
    
    const VBS_NAME = 'Name';
    const VBS_DISPLAY_NAME = 'DisplayName';
    const VBS_DESCRIPTION = 'Description';
    const VBS_PATH_NAME = 'PathName';
    const VBS_STATE = 'State';
    
    private $name;
    private $displayName;
    private $binPath;
    private $params;
    private $startType;
    private $errorControl;
    private $nssm;
    
    private $latestStatus;
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
    
    public static function getVbsKeys()
    {
        return array(
            self::VBS_NAME,
            self::VBS_DISPLAY_NAME,
            self::VBS_DESCRIPTION,
            self::VBS_PATH_NAME,
            self::VBS_STATE
        );
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
    
    public function status($timeout = true)
    {
        usleep(self::SLEEP_TIME);
    
        $this->latestStatus = self::WIN32_SERVICE_NA;
        $maxtime = time() + self::PENDING_TIMEOUT;
    
        while ($this->latestStatus == self::WIN32_SERVICE_NA || $this->isPending($this->latestStatus)) {
            $this->latestStatus = $this->callWin32Service('win32_query_service_status', $this->getName());
            if (is_array($this->latestStatus) && isset($this->latestStatus['CurrentState'])) {
                $this->latestStatus = dechex($this->latestStatus['CurrentState']);
            } elseif (dechex($this->latestStatus) == self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST) {
                $this->latestStatus = dechex($this->latestStatus);
            }
            if ($timeout && $maxtime < time()) {
                break;
            }
        }
        
        if ($this->latestStatus == self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST) {
            $this->latestError = $this->latestStatus;
            $this->latestStatus = self::WIN32_SERVICE_NA;
        }
    
        return $this->latestStatus;
    }

    public function create()
    {
        global $neardBins;
        
        if ($this->getNssm() instanceof Nssm) {
            return $this->getNssm()->create();
        } elseif ($this->getName() == BinFilezilla::SERVICE_NAME) {
            $neardBins->getFilezilla()->rebuildConf();
            return Batch::installFilezillaService();
        } elseif ($this->getName() == BinPostgresql::SERVICE_NAME) {
            $neardBins->getPostgresql()->rebuildConf();
            $neardBins->getPostgresql()->initData();
            return Batch::installPostgresqlService();
        }
        
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
        
        if ($create != self::WIN32_NO_ERROR) {
            return false;
        } elseif (!$this->isInstalled()) {
            $this->latestError = self::WIN32_NO_ERROR;
            return false;
        }
        return true;
    }

    public function delete()
    {
        if (!$this->isInstalled()) {
            return true;
        }
        
        $this->stop();
        
        if ($this->getName() == BinFilezilla::SERVICE_NAME) {
            return Batch::uninstallFilezillaService();
        } elseif ($this->getName() == BinPostgresql::SERVICE_NAME) {
            return Batch::uninstallPostgresqlService();
        }
        
        $delete = dechex($this->callWin32Service('win32_delete_service', $this->getName(), true));
        $this->writeLog('Delete service ' . $this->getName() . ': ' . $delete . ' (status: ' . $this->status() . ')');
        
        if ($delete != self::WIN32_NO_ERROR && $delete != self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST) {
            return false;
        } elseif ($this->isInstalled()) {
            $this->latestError = self::WIN32_NO_ERROR;
            return false;
        }
        return true;
    }
    
    public function reset()
    {
        if ($this->delete()) {
            usleep(self::SLEEP_TIME);
            return $this->create();
        }
        return false;
    }
    
    public function start()
    {
        global $neardBins;
        
        if ($this->getName() == BinFilezilla::SERVICE_NAME) {
            $neardBins->getFilezilla()->rebuildConf();
        } elseif ($this->getName() == BinMysql::SERVICE_NAME) {
            $neardBins->getMysql()->initData();
        } elseif ($this->getName() == BinMailhog::SERVICE_NAME) {
            $neardBins->getMailhog()->rebuildConf();
        } elseif ($this->getName() == BinMemcached::SERVICE_NAME) {
            $neardBins->getMemcached()->rebuildConf();
        } elseif ($this->getName() == BinPostgresql::SERVICE_NAME) {
            $neardBins->getPostgresql()->rebuildConf();
            $neardBins->getPostgresql()->initData();
        }
        
        $start = dechex($this->callWin32Service('win32_start_service', $this->getName(), true));
        $this->writeLog('Start service ' . $this->getName() . ': ' . $start . ' (status: ' . $this->status() . ')');
    
        if ($start != self::WIN32_NO_ERROR && $start != self::WIN32_ERROR_SERVICE_ALREADY_RUNNING) {
            if ($this->getName() == BinApache::SERVICE_NAME) {
                $cmdOutput = $neardBins->getApache()->getCmdLineOutput(BinApache::CMD_SYNTAX_CHECK);
                if (!$cmdOutput['syntaxOk']) {
                    file_put_contents(
                        $neardBins->getApache()->getErrorLog(),
                        '[' . date('Y-m-d H:i:s', time()) . '] [error] ' . $cmdOutput['content'] . PHP_EOL,
                        FILE_APPEND
                    );
                }
            } elseif ($this->getName() == BinMysql::SERVICE_NAME) {
                $cmdOutput = $neardBins->getMysql()->getCmdLineOutput(BinMysql::CMD_SYNTAX_CHECK);
                if (!$cmdOutput['syntaxOk']) {
                    file_put_contents(
                        $neardBins->getMysql()->getErrorLog(),
                        '[' . date('Y-m-d H:i:s', time()) . '] [error] ' . $cmdOutput['content'] . PHP_EOL,
                        FILE_APPEND
                    );
                }
            } elseif ($this->getName() == BinMariadb::SERVICE_NAME) {
                $cmdOutput = $neardBins->getMariadb()->getCmdLineOutput(BinMariadb::CMD_SYNTAX_CHECK);
                if (!$cmdOutput['syntaxOk']) {
                    file_put_contents(
                        $neardBins->getMariadb()->getErrorLog(),
                        '[' . date('Y-m-d H:i:s', time()) . '] [error] ' . $cmdOutput['content'] . PHP_EOL,
                        FILE_APPEND
                    );
                }
            }
            return false;
        } elseif (!$this->isRunning()) {
            $this->latestError = self::WIN32_NO_ERROR;
            return false;
        }
        return true;
    }
    
    public function stop()
    {
        $stop = dechex($this->callWin32Service('win32_stop_service', $this->getName(), true));
        $this->writeLog('Stop service ' . $this->getName() . ': ' . $stop . ' (status: ' . $this->status() . ')');
    
        if ($stop != self::WIN32_NO_ERROR) {
            return false;
        } elseif (!$this->isStopped()) {
            $this->latestError = self::WIN32_NO_ERROR;
            return false;
        }
        return true;
    }
    
    public function restart()
    {
        if ($this->stop()) {
            return $this->start();
        }
        return false;
    }
    
    public function infos()
    {
        if ($this->getNssm() instanceof Nssm) {
            return $this->getNssm()->infos();
        }
        
        return Vbs::getServiceInfos($this->getName());
    }

    public function isInstalled()
    {
        $status = $this->status();
        $this->writeLog('isInstalled ' . $this->getName() . ': ' . ($status != self::WIN32_SERVICE_NA ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status != self::WIN32_SERVICE_NA;
    }
    
    public function isRunning()
    {
        $status = $this->status();
        $this->writeLog('isRunning ' . $this->getName() . ': ' . ($status == self::WIN32_SERVICE_RUNNING ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::WIN32_SERVICE_RUNNING;
    }
    
    public function isStopped()
    {
        $status = $this->status();
        $this->writeLog('isStopped ' . $this->getName() . ': ' . ($status == self::WIN32_SERVICE_STOPPED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::WIN32_SERVICE_STOPPED;
    }
    
    public function isPaused()
    {
        $status = $this->status();
        $this->writeLog('isPaused ' . $this->getName() . ': ' . ($status == self::WIN32_SERVICE_PAUSED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::WIN32_SERVICE_PAUSED;
    }
    
    public function isPending($status)
    {
        return $status == self::WIN32_SERVICE_START_PENDING || $status == self::WIN32_SERVICE_STOP_PENDING
            || $status == self::WIN32_SERVICE_CONTINUE_PENDING || $status == self::WIN32_SERVICE_PAUSE_PENDING;
    }
    
    private function getWin32ServiceStatusDesc($status)
    {
        switch ($status) {
            case self::WIN32_SERVICE_CONTINUE_PENDING:
                return 'The service continue is pending.';
                break;
                
            case self::WIN32_SERVICE_PAUSE_PENDING:
                return 'The service pause is pending.';
                break;
                    
            case self::WIN32_SERVICE_PAUSED:
                return 'The service is paused.';
                break;
                        
            case self::WIN32_SERVICE_RUNNING:
                return 'The service is running.';
                break;
                            
            case self::WIN32_SERVICE_START_PENDING:
                return 'The service is starting.';
                break;
                                
            case self::WIN32_SERVICE_STOP_PENDING:
                return 'The service is stopping.';
                break;
                
            case self::WIN32_SERVICE_STOPPED:
                return 'The service is not running.';
                break;
                
            case self::WIN32_SERVICE_NA:
                return 'Cannot retrieve service status.';
                break;
                
            default:
                return null;
                break;
        }
    }
    
    private function getWin32ErrorCodeDesc($code)
    {
        switch ($code) {
            case self::WIN32_ERROR_ACCESS_DENIED:
                return 'The handle to the SCM database does not have the appropriate access rights.';
                break;
                
            case self::WIN32_ERROR_CIRCULAR_DEPENDENCY:
                return 'A circular service dependency was specified.';
                break;
            
            case self::WIN32_ERROR_DATABASE_DOES_NOT_EXIST:
                return 'The specified database does not exist.';
                break;
            
            case self::WIN32_ERROR_DEPENDENT_SERVICES_RUNNING:
                return 'The service cannot be stopped because other running services are dependent on it.';
                break;
                
            case self::WIN32_ERROR_DUPLICATE_SERVICE_NAME:
                return 'The display name already exists in the service control manager database either as a service name or as another display name.';
                break;
                
            case self::WIN32_ERROR_FAILED_SERVICE_CONTROLLER_CONNECT:
                return 'This error is returned if the program is being run as a console application rather than as a service. If the program will be run as a console application for debugging purposes, structure it such that service-specific code is not called.';
                break;
                
            case self::WIN32_ERROR_INSUFFICIENT_BUFFER:
                return 'The buffer is too small for the service status structure. Nothing was written to the structure.';
                break;
            
            case self::WIN32_ERROR_INVALID_DATA:
                return 'The specified service status structure is invalid. ';
                break;
            
            case self::WIN32_ERROR_INVALID_HANDLE:
                return 'The handle to the specified service control manager database is invalid.';
                break;
            
            case self::WIN32_ERROR_INVALID_LEVEL:
                return 'The InfoLevel parameter contains an unsupported value.';
                break;
            
            case self::WIN32_ERROR_INVALID_NAME:
                return 'The specified service name is invalid.';
                break;
                
            case self::WIN32_ERROR_INVALID_PARAMETER:
                return 'A parameter that was specified is invalid.';
                break;
            
            case self::WIN32_ERROR_INVALID_SERVICE_ACCOUNT:
                return 'The user account name specified in the user parameter does not exist.';
                break;
            
            case self::WIN32_ERROR_INVALID_SERVICE_CONTROL:
                return 'The requested control code is not valid, or it is unacceptable to the service.';
                break;
            
            case self::WIN32_ERROR_PATH_NOT_FOUND:
                return 'The service binary file could not be found.';
                break;
            
            case self::WIN32_ERROR_SERVICE_ALREADY_RUNNING:
                return 'An instance of the service is already running.';
                break;
                
            case self::WIN32_ERROR_SERVICE_CANNOT_ACCEPT_CTRL:
                return 'The requested control code cannot be sent to the service because the state of the service is WIN32_SERVICE_STOPPED, WIN32_SERVICE_START_PENDING, or WIN32_SERVICE_STOP_PENDING.';
                break;
            
            case self::WIN32_ERROR_SERVICE_DATABASE_LOCKED:
                return 'The database is locked.';
                break;
            
            case self::WIN32_ERROR_SERVICE_DEPENDENCY_DELETED:
                return 'The service depends on a service that does not exist or has been marked for deletion.';
                break;
            
            case self::WIN32_ERROR_SERVICE_DEPENDENCY_FAIL:
                return 'The service depends on another service that has failed to start.';
                break;
            
            case self::WIN32_ERROR_SERVICE_DISABLED:
                return 'The service has been disabled.';
                break;
            
            case self::WIN32_ERROR_SERVICE_DOES_NOT_EXIST:
                return 'The specified service does not exist as an installed service.';
                break;
            
            case self::WIN32_ERROR_SERVICE_EXISTS:
                return 'The specified service already exists in this database.';
                break;
            
            case self::WIN32_ERROR_SERVICE_LOGON_FAILED:
                return 'The service did not start due to a logon failure. This error occurs if the service is configured to run under an account that does not have the "Log on as a service" right.';
                break;
            
            case self::WIN32_ERROR_SERVICE_MARKED_FOR_DELETE:
                return 'The specified service has already been marked for deletion.';
                break;
            
            case self::WIN32_ERROR_SERVICE_NO_THREAD:
                return 'A thread could not be created for the service.';
                break;
            
            case self::WIN32_ERROR_SERVICE_NOT_ACTIVE:
                return 'The service has not been started.';
                break;
            
            case self::WIN32_ERROR_SERVICE_REQUEST_TIMEOUT:
                return 'The process for the service was started, but it did not call StartServiceCtrlDispatcher, or the thread that called StartServiceCtrlDispatcher may be blocked in a control handler function.';
                break;
            
            case self::WIN32_ERROR_SHUTDOWN_IN_PROGRESS:
                return 'The system is shutting down; this function cannot be called.';
                break;
                
            default:
                return null;
                break;
        }
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function getBinPath()
    {
        return $this->binPath;
    }

    public function setBinPath($binPath)
    {
        $this->binPath = str_replace('"', '', Util::formatWindowsPath($binPath));
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getStartType()
    {
        return $this->startType;
    }

    public function setStartType($startType)
    {
        $this->startType = $startType;
    }

    public function getErrorControl()
    {
        return $this->errorControl;
    }

    public function setErrorControl($errorControl)
    {
        $this->errorControl = $errorControl;
    }
    
    public function getNssm()
    {
        return $this->nssm;
    }
    
    public function setNssm($nssm)
    {
        if ($nssm instanceof Nssm) {
            $this->setDisplayName($nssm->getDisplayName());
            $this->setBinPath($nssm->getBinPath());
            $this->setParams($nssm->getParams());
            $this->setStartType($nssm->getStart());
            $this->nssm = $nssm;
        }
    }
    
    public function getLatestStatus()
    {
        return $this->latestStatus;
    }

    public function getLatestError()
    {
        return $this->latestError;
    }
    
    public function getError()
    {
        global $neardLang;
        if ($this->latestError != self::WIN32_NO_ERROR) {
            return $neardLang->getValue(Lang::ERROR) . ' ' .
                $this->latestError . ' (' . hexdec($this->latestError) . ' : ' . $this->getWin32ErrorCodeDesc($this->latestError) . ')';
        } elseif ($this->latestStatus != self::WIN32_SERVICE_NA) {
            return $neardLang->getValue(Lang::STATUS) . ' ' .
                $this->latestStatus . ' (' . hexdec($this->latestStatus) . ' : ' . $this->getWin32ServiceStatusDesc($this->latestStatus) . ')';
        }
        return null;
    }
}
