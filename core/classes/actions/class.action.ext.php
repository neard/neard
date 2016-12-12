<?php

class ActionExt
{
    const START = 'start';
    const STOP = 'stop';
    const RELOAD = 'reload';
    const REFRESH = 'refresh';
    
    const STATUS_ERROR = 2;
    const STATUS_WARNING = 1;
    const STATUS_SUCCESS = 0;
    
    private $status = self::STATUS_SUCCESS;
    private $logs = '';
    
    public function __construct($args)
    {
        if (!isset($args[0]) || empty($args[0])) {
            $this->addLog('No args defined');
            $this->addLog('Available args:');
            foreach ($this->getProcs() as $proc) {
                $this->addLog('- ' . $proc);
            }
            $this->setStatus(self::STATUS_ERROR);
            $this->sendLogs();
            return;
        }
        
        $action = $args[0];
        
        $newArgs = array();
        foreach ($args as $key => $arg) {
            if ($key > 0) {
                $newArgs[] = $arg;
            }
        }
        
        $method = 'proc' . ucfirst($action);
        if (!method_exists($this, $method)) {
            $this->addLog('Unknown arg: ' . $action);
            $this->addLog('Available args:');
            foreach ($this->getProcs() as $procName => $procDesc) {
                $this->addLog('- ' . $procName . ': ' . $procDesc);
            }
            $this->setStatus(self::STATUS_ERROR);
            $this->sendLogs();
            return;
        }
        
        call_user_func(array($this, $method), $newArgs);
        $this->sendLogs();
    }
    
    private function getProcs()
    {
        return array(
            self::START,
            self::STOP,
            self::RELOAD,
            self::REFRESH
        );
    }
    
    private function addLog($data)
    {
        $this->logs .= $data . "\n";
    }
    
    private function setStatus($status)
    {
        $this->status = $status;
    }
    
    private function sendLogs()
    {
        echo json_encode(array(
            'status' => $this->status,
            'response' => $this->logs
        ));
    }
    
    private function procStart($args)
    {
        global $neardBs, $neardWinbinder;
        
        if (!Util::isLaunched()) {
            $this->addLog('Starting ' . APP_TITLE);
            $neardWinbinder->exec($neardBs->getExeFilePath(), null, false);
        } else {
            $this->addLog(APP_TITLE . ' already started');
            $this->setStatus(self::STATUS_WARNING);
        }
    }
    
    private function procStop($args)
    {
        global $neardBins;
        
        if (Util::isLaunched()) {
            $this->addLog('Remove services');
            foreach ($neardBins->getServices() as $sName => $service) {
                if ($service->delete()) {
                    $this->addLog('- ' . $sName . ': OK');
                } else {
                    $this->addLog('- ' . $sName . ': KO');
                    $this->setStatus(self::STATUS_ERROR);
                }
            }
        
            $this->addLog('Stop ' . APP_TITLE);
            Batch::exitAppStandalone();
        } else {
            $this->addLog(APP_TITLE . ' already stopped');
            $this->setStatus(self::STATUS_WARNING);
        }
    }
    
    private function procReload($args)
    {
        global $neardBs, $neardBins, $neardWinbinder;
        
        if (!Util::isLaunched()) {
            $this->addLog(APP_TITLE . ' is not started.');
            $neardWinbinder->exec($neardBs->getExeFilePath(), null, false);
            $this->addLog('Start ' . APP_TITLE);
            $this->setStatus(self::STATUS_WARNING);
            return;
        }
        
        $this->addLog('Remove services');
        foreach ($neardBins->getServices() as $sName => $service) {
            if ($service->delete()) {
                $this->addLog('- ' . $sName . ': OK');
            } else {
                $this->addLog('- ' . $sName . ': KO');
                $this->setStatus(self::STATUS_ERROR);
            }
        }
        
        Win32Ps::killBins();
        
        $this->addLog('Start services');
        foreach ($neardBins->getServices() as $sName => $service) {
            $service->create();
            if ($service->start()) {
                $this->addLog('- ' . $sName . ': OK');
            } else {
                $this->addLog('- ' . $sName . ': KO');
                $this->setStatus(self::STATUS_ERROR);
            }
        }
    }
    
    private function procRefresh($args)
    {
        global $neardAction;
        
        if (!Util::isLaunched()) {
            $this->addLog(APP_TITLE . ' is not started.');
            $this->setStatus(self::STATUS_ERROR);
            return;
        }
        
        $neardAction->call(Action::RELOAD);
    }
}
