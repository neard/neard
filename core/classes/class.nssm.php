<?php

class Nssm
{
    // Start params
    const SERVICE_AUTO_START = 'SERVICE_AUTO_START';
    const SERVICE_DELAYED_START = 'SERVICE_DELAYED_START';
    const SERVICE_DEMAND_START = 'SERVICE_DEMAND_START';
    const SERVICE_DISABLED = 'SERVICE_DISABLED';
    
    // Type params
    const SERVICE_WIN32_OWN_PROCESS = 'SERVICE_WIN32_OWN_PROCESS';
    const SERVICE_INTERACTIVE_PROCESS = 'SERVICE_INTERACTIVE_PROCESS';
    
    // Status
    const STATUS_CONTINUE_PENDING = 'SERVICE_CONTINUE_PENDING';
    const STATUS_PAUSE_PENDING = 'SERVICE_PAUSE_PENDING';
    const STATUS_PAUSED = 'SERVICE_PAUSED';
    const STATUS_RUNNING = 'SERVICE_RUNNING';
    const STATUS_START_PENDING = 'SERVICE_START_PENDING';
    const STATUS_STOP_PENDING = 'SERVICE_STOP_PENDING';
    const STATUS_STOPPED = 'SERVICE_STOPPED';
    const STATUS_NOT_EXIST = 'SERVICE_NOT_EXIST';
    const STATUS_NA = '-1';
    
    // Infos keys
    const INFO_APP_DIRECTORY = 'AppDirectory';
    const INFO_APPLICATION = 'Application';
    const INFO_APP_PARAMETERS = 'AppParameters';
    const INFO_APP_STDERR = 'AppStderr';
    const INFO_APP_STDOUT = 'AppStdout';
    const INFO_APP_ENVIRONMENT_EXTRA = 'AppEnvironmentExtra';
    
    const PENDING_TIMEOUT = 10;
    const SLEEP_TIME = 500000;
    
    private $name;
    private $displayName;
    private $binPath;
    private $params;
    private $start;
    private $stdout;
    private $stderr;
    private $environmentExtra;
    private $latestError;
    private $latestStatus;
    
    public function __construct($name)
    {
        Util::logInitClass($this);
        $this->name = $name;
    }
    
    private function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getNssmLogFilePath());
    }
    
    private function writeLogInfo($log)
    {
        global $neardBs;
        Util::logInfo($log, $neardBs->getNssmLogFilePath());
    }
    
    private function writeLogError($log)
    {
        global $neardBs;
        Util::logError($log, $neardBs->getNssmLogFilePath());
    }
    
    private function exec($args)
    {
        global $neardCore;
        
        $command = '"' . $neardCore->getNssmExe() . '" ' . $args;
        $this->writeLogInfo('Cmd: ' . $command);
        
        $result = Batch::exec('nssm', $command, 10);
        if (is_array($result)) {
            $rebuildResult = array();
            foreach ($result as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $rebuildResult[] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row);
                }
            }
            $result = $rebuildResult;
            if (count($result) > 1) {
                $this->latestError = implode(' ; ', $result);
            }
            return $result;
        }
        
        return false;
    }
    
    public function status($timeout = true)
    {
        usleep(self::SLEEP_TIME);
    
        $this->latestStatus = self::STATUS_NA;
        $maxtime = time() + self::PENDING_TIMEOUT;
    
        while ($this->latestStatus == self::STATUS_NA || $this->isPending($this->latestStatus)) {
            $exec = $this->exec('status ' . $this->getName());
            if ($exec !== false) {
                if (count($exec) > 1) {
                    $this->latestStatus = self::STATUS_NOT_EXIST;
                } else {
                    $this->latestStatus = $exec[0];
                }
            }
            if ($timeout && $maxtime < time()) {
                break;
            }
        }
        
        if ($this->latestStatus == self::STATUS_NOT_EXIST) {
            $this->latestError = 'Error 3: The specified service does not exist as an installed service.';
            $this->latestStatus = self::STATUS_NA;
        }
    
        return $this->latestStatus;
    }

    public function create()
    {
        $this->writeLog('Create service');
        $this->writeLog('-> service: ' . $this->getName());
        $this->writeLog('-> display: ' . $this->getDisplayName());
        $this->writeLog('-> description: ' . $this->getDisplayName());
        $this->writeLog('-> path: ' . $this->getBinPath());
        $this->writeLog('-> params: ' . $this->getParams());
        $this->writeLog('-> stdout: ' . $this->getStdout());
        $this->writeLog('-> stderr: ' . $this->getStderr());
        $this->writeLog('-> environment extra: ' . $this->getEnvironmentExtra());
        $this->writeLog('-> start_type: ' . ($this->getStart() != null ? $this->getStart() : self::SERVICE_DEMAND_START));
        
        // Install bin
        $exec = $this->exec('install ' . $this->getName() . ' "' . $this->getBinPath() . '"');
        if ($exec === false) {
            return false;
        }
        
        // Params
        $exec = $this->exec('set ' . $this->getName() . ' AppParameters "' . $this->getParams() . '"');
        if ($exec === false) {
            return false;
        }
        
        // DisplayName
        $exec = $this->exec('set ' . $this->getName() . ' DisplayName "' . $this->getDisplayName() . '"');
        if ($exec === false) {
            return false;
        }
        
        // Description
        $exec = $this->exec('set ' . $this->getName() . ' Description "' . $this->getDisplayName() . '"');
        if ($exec === false) {
            return false;
        }
        
        // No AppNoConsole to fix nssm problems with Windows 10 Creators update.
        $exec = $this->exec('set ' . $this->getName() . ' AppNoConsole "1"');
        if ($exec === false) {
            return false;
        }
        
        // Start
        $exec = $this->exec('set ' . $this->getName() . ' Start "' . ($this->getStart() != null ? $this->getStart() : self::SERVICE_DEMAND_START) . '"');
        if ($exec === false) {
            return false;
        }
        
        // Stdout
        $exec = $this->exec('set ' . $this->getName() . ' AppStdout "' . $this->getStdout() . '"');
        if ($exec === false) {
            return false;
        }
        
        // Stderr
        $exec = $this->exec('set ' . $this->getName() . ' AppStderr "' . $this->getStderr() . '"');
        if ($exec === false) {
            return false;
        }
        
        // Environment Extra
        $exec = $this->exec('set ' . $this->getName() . ' AppEnvironmentExtra ' . $this->getEnvironmentExtra());
        if ($exec === false) {
            return false;
        }
        
        if (!$this->isInstalled()) {
            $this->latestError = null;
            return false;
        }
        
        return true;
    }

    public function delete()
    {
        $this->stop();
        
        $this->writeLog('Delete service ' . $this->getName());
        $exec = $this->exec('remove ' . $this->getName() . ' confirm');
        if ($exec === false) {
            return false;
        }
        
        if ($this->isInstalled()) {
            $this->latestError = null;
            return false;
        }
        
        return true;
    }
    
    public function start()
    {
        $this->writeLog('Start service ' . $this->getName());
        
        $exec = $this->exec('start ' . $this->getName());
        if ($exec === false) {
            return false;
        }
        
        if (!$this->isRunning()) {
            $this->latestError = null;
            return false;
        }
        
        return true;
    }
    
    public function stop()
    {
        $this->writeLog('Stop service ' . $this->getName());
        
        $exec = $this->exec('stop ' . $this->getName());
        if ($exec === false) {
            return false;
        }
        
        if (!$this->isStopped()) {
            $this->latestError = null;
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
        global $neardRegistry;
        
        $infos = Vbs::getServiceInfos($this->getName());
        if ($infos === false) {
            return false;
        }
        
        $infosNssm = array();
        $infosKeys = array(
            self::INFO_APPLICATION,
            self::INFO_APP_PARAMETERS,
        );
            
        foreach ($infosKeys as $infoKey) {
            $value = null;
            $exists = $neardRegistry->exists(
                Registry::HKEY_LOCAL_MACHINE,
                'SYSTEM\CurrentControlSet\Services\\' . $this->getName() . '\Parameters',
                $infoKey
            );
            if ($exists) {
                $value = $neardRegistry->getValue(
                    Registry::HKEY_LOCAL_MACHINE,
                    'SYSTEM\CurrentControlSet\Services\\' . $this->getName() . '\Parameters',
                    $infoKey
                );
            }
            $infosNssm[$infoKey] = $value;
        }
        
        if (!isset($infosNssm[self::INFO_APPLICATION])) {
            return $infos;
        }
        
        $infos[Win32Service::VBS_PATH_NAME] = $infosNssm[Nssm::INFO_APPLICATION] . ' ' . $infosNssm[Nssm::INFO_APP_PARAMETERS];
        return $infos;
    }

    public function isInstalled()
    {
        $status = $this->status();
        $this->writeLog('isInstalled ' . $this->getName() . ': ' . ($status != self::STATUS_NA ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status != self::STATUS_NA;
    }
    
    public function isRunning()
    {
        $status = $this->status();
        $this->writeLog('isRunning ' . $this->getName() . ': ' . ($status == self::STATUS_RUNNING ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::STATUS_RUNNING;
    }
    
    public function isStopped()
    {
        $status = $this->status();
        $this->writeLog('isStopped ' . $this->getName() . ': ' . ($status == self::STATUS_STOPPED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::STATUS_STOPPED;
    }
    
    public function isPaused()
    {
        $status = $this->status();
        $this->writeLog('isPaused ' . $this->getName() . ': ' . ($status == self::STATUS_PAUSED ? 'YES' : 'NO') . ' (status: ' . $status . ')');
        return $status == self::STATUS_PAUSED;
    }
    
    public function isPending($status)
    {
        return $status == self::STATUS_START_PENDING || $status == self::STATUS_STOP_PENDING
            || $status == self::STATUS_CONTINUE_PENDING || $status == self::STATUS_PAUSE_PENDING;
    }
    
    private function getServiceStatusDesc($status)
    {
        switch ($status) {
            case self::STATUS_CONTINUE_PENDING:
                return 'The service continue is pending.';
                break;
                
            case self::STATUS_PAUSE_PENDING:
                return 'The service pause is pending.';
                break;
                    
            case self::STATUS_PAUSED:
                return 'The service is paused.';
                break;
                        
            case self::STATUS_RUNNING:
                return 'The service is running.';
                break;
                            
            case self::STATUS_START_PENDING:
                return 'The service is starting.';
                break;
                                
            case self::STATUS_STOP_PENDING:
                return 'The service is stopping.';
                break;
                
            case self::STATUS_STOPPED:
                return 'The service is not running.';
                break;
                
            case self::STATUS_NA:
                return 'Cannot retrieve service status.';
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

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function getStdout()
    {
        return $this->stdout;
    }

    public function setStdout($stdout)
    {
        $this->stdout = $stdout;
    }
    
    public function getStderr()
    {
        return $this->stderr;
    }
    
    public function setStderr($stderr)
    {
        $this->stderr= $stderr;
    }
    
    public function getEnvironmentExtra()
    {
        return $this->environmentExtra;
    }
    
    public function setEnvironmentExtra($environmentExtra)
    {
        $this->environmentExtra = Util::formatWindowsPath($environmentExtra);
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
        
        if (!empty($this->latestError)) {
            return $neardLang->getValue(Lang::ERROR) . ' ' . $this->latestError;
        } elseif ($this->latestStatus != self::STATUS_NA) {
            return $neardLang->getValue(Lang::STATUS) . ' ' . $this->latestStatus . ' : ' . $this->getWin32ServiceStatusDesc($this->latestStatus);
        }
        
        return null;
    }
}
