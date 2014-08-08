<?php

class Batch
{
    const END_PROCESS_STR = 'FINISHED!';
    const CATCH_OUTPUT_FALSE = 'neardCatchOutputFalse';
    
    public function __construct()
    {
        
    }
    
    private static function writeLog($log)
    {
        global $neardBs;
        Util::logDebug($log, $neardBs->getBatchLogFilePath());
    }
    
    public static function findExeByPid($pid)
    {
        $result = self::exec('findExeByPid', 'TASKLIST /FO CSV /NH /FI "PID eq ' . $pid . '"', 2);
        if ($result !== false) {
            $expResult = explode('","', $result[0]);
            if (is_array($expResult) && count($expResult) > 2 && isset($expResult[0]) && !empty($expResult[0])) {
                return substr($expResult[0], 1);
            }
        }
        
        return false;
    }
    
    public static function isPortInUse($port)
    {
        $result = self::exec('isPortInUse', 'NETSTAT -aon | FIND ":' . $port . '" | FIND "LISTENING"', 1);
        if ($result !== false && isset($result[0]) && !empty($result[0])) {
            $expResult = explode(' ', preg_replace('/\s+/', ' ', $result[0]));
            $pid = intval($expResult[4]);
            $exe = self::findExeByPid($pid);
            if ($exe !== false) {
                return $exe . ' (' . $pid . ')';
            }
            return $pid;
        }
        
        return false;
        //return @fsockopen('127.0.0.1', intval($port)) !== false;
    }
    
    public static function exitApp($restart = false)
    {
        global $neardBs, $neardCore;
    
        $content = 'PING 1.1.1.1 -n 1 -w 2000 > nul' . PHP_EOL;
        $content .= '"' . $neardBs->getExeFilePath() . '" -quit -id={neard}' . PHP_EOL;
        if ($restart) {
            $basename = 'restartApp';
            Util::logInfo('Restart App');
            $content .= '"' . $neardCore->getPhpCliSilentExe() . '" "' . Core::BOOTSTRAP_FILE . '" "' . Action::RESTART . '"' . PHP_EOL;
        } else {
            $basename = 'exitApp';
            Util::logInfo('Exit App');
        }
        
        self::execStandalone($basename, $content);
    }
    
    public static function restartApp()
    {
        self::exitApp(true);
    }
    
    public static function getPearVersion()
    {
        global $neardBins;
        
        $result = self::exec('getPearVersion', 'CMD /C "' . $neardBins->getPhp()->getPearExe() . '" -V', 2);
        if (is_array($result)) {
            foreach ($result as $row) {
                if (Util::startWith($row, 'PEAR Version:')) {
                    $expResult = explode(' ', $row);
                    if (count($expResult) == 3) {
                        return trim($expResult[2]);
                    }
                }
            }
        }
        
        return null;
    }
    
    public static function getSvnVersion()
    {
        global $neardTools;
    
        $result = self::exec('getSvnVersion', 'CMD /C "' . $neardTools->getSvn()->getExe() . '" --version', 2);
        if (!empty($result) && is_array($result)) {
            $rebuildResult = array();
            foreach ($result as $row) {
                $rebuildResult[] = Util::cp1252ToUtf8($row);
            }
            $result = $rebuildResult;
        }
    
        return $result;
    }
    
    public static function downloadWget($url, $genFile = true)
    {
        global $neardCore;
    
        $result = self::exec('wget',
            '"' . $neardCore->getWgetExe() . '" -q --no-check-certificate -O - "' . $url . '"',
            10, true, false, true, false
        );
        
        if (!empty($result) && is_array($result)) {
            array_pop($result);
            array_pop($result);
            array_pop($result);
            $result = implode('', $result);
        }
        
        if ($genFile && !empty($result)) {
            $resultFile = self::getTmpFile('.tmp', 'wget');
            file_put_contents($resultFile, $result);
            return $resultFile;
        }
    
        return $result;
    }
    
    public static function refreshEnvVars()
    {
        global $neardBs, $neardCore;
        
        self::execStandalone('refreshEnvVars', 'SETX /M ' . Registry::APP_PATH_REG_ENTRY . ' "' . Util::formatWindowsPath($neardBs->getRootPath()) . '"');
    }
    
    public static function genApacheCertificate($domain = 'localhost')
    {
        global $neardBs, $neardBins;
        
        $subject = '"/C=FR/O=neard/CN=' . $domain . '"';
        $password = 'pass:neard';
        $ppkPath = '"' . $neardBs->getSslPath() . '/' . $domain . '.ppk"';
        $pubPath = '"' . $neardBs->getSslPath() . '/' . $domain . '.pub"';
        $crtPath = '"' . $neardBs->getSslPath() . '/' . $domain . '.crt"';
        $exe = '"' . $neardBins->getApache()->getOpensslExe() . '"';
        $conf = '"' . $neardBs->getSslConfPath() . '"';
        
        $cmdGeneratingKey = $exe . ' genrsa -des3 -passout ' . $password . ' -out ' . $ppkPath . ' 2048 -noout -config ' . $conf . PHP_EOL;
        $cmdGeneratingPub = $exe . ' rsa -in ' . $ppkPath . ' -passin ' . $password . ' -out ' . $pubPath . PHP_EOL;
        $cmdCreateCrt = $exe . ' req -x509 -nodes -sha1 -new -key ' . $pubPath . ' -out ' . $crtPath . ' -passin ' . $password . ' -subj ' . $subject . ' -config ' . $conf . PHP_EOL;
        self::exec('genCertificate', $cmdGeneratingKey . $cmdGeneratingPub . $cmdCreateCrt, 5);
        
        $result = self::exec('checkCertificate', '@ECHO ON' . PHP_EOL . 'IF EXIST ' . $pubPath . ' IF EXIST ' . $crtPath . ' ECHO OK', 2);
        return isset($result[0]) && $result[0] == 'OK';
    }
    
    public static function execStandalone($basename, $content, $silent = true)
    {
        return self::exec($basename, $content, 0, false, true, $silent);
    }
    
    public static function exec($basename, $content, $timeout, $catchOutput = true, $standalone = false, $silent = true, $rebuild = true)
    {
        global $neardCore, $neardWinbinder;
        $result = false;
    
        $resultFile = self::getTmpFile('.tmp', $basename);
        $scriptPath = self::getTmpFile('.bat', $basename);
        $checkFile = self::getTmpFile('.tmp', $basename);
    
        // Redirect output
        if ($catchOutput) {
            $content .= '> "' . $resultFile . '"' . (!Util::endWith($content, '2') ? ' 2>&1' : '');
        }
        
        // Header
        $header = '@ECHO OFF' . PHP_EOL . PHP_EOL;
            //'@SETLOCAL ENABLEEXTENSIONS ENABLEDELAYEDEXPANSION' . PHP_EOL . PHP_EOL;
        
        // Footer
        //$footer = PHP_EOL . PHP_EOL . 'ENDLOCAL' .
        $footer = PHP_EOL . (!$standalone ? PHP_EOL . 'ECHO ' . self::END_PROCESS_STR . ' > "' . $checkFile . '"' : '') .
            PHP_EOL . 'DEL /F "' . $scriptPath . '"';
        
        // Process
        file_put_contents($scriptPath, $header . $content . $footer);
        $neardWinbinder->exec($scriptPath, null, $silent);
        
        if (!$standalone) {
            $maxtime = time() + $timeout;
            $noTimeout = $timeout == 0;
            while ($result === false || empty($result)) {
                if (file_exists($checkFile)) {
                    $check = file($checkFile);
                    if (!empty($check) && trim($check[0]) == self::END_PROCESS_STR) {
                        if ($catchOutput && file_exists($resultFile)) {
                            $result = file($resultFile);
                        } else {
                            $result = self::CATCH_OUTPUT_FALSE;
                        }
                    }
                }
                if ($maxtime < time() && !$noTimeout) {
                    break;
                }
            }
            @unlink($scriptPath);
        }
        
        @unlink($checkFile);
        @unlink($resultFile);
        
        self::writeLog('Exec:');
        self::writeLog('-> basename: ' . $basename);
        self::writeLog('-> content: ' . str_replace(PHP_EOL, ' \\\\ ', $content));
        self::writeLog('-> checkFile: ' . $checkFile);
        self::writeLog('-> resultFile: ' . $resultFile);
        self::writeLog('-> scriptPath: ' . $scriptPath);
        
        if ($result !== false && !empty($result) && is_array($result)) {
            if ($rebuild) {
                $rebuildResult = array();
                foreach ($result as $row) {
                    $row = trim($row);
                    if (!empty($row)) {
                        $rebuildResult[] = $row;
                    }
                }
                $result = $rebuildResult;
            }
            self::writeLog('-> result: ' . substr(implode(' \\\\ ', $result), 0, 2048));
        } else {
            self::writeLog('-> result: NULL');
        }
        
        return $result;
    }
    
    private static function getTmpFile($ext, $customName = null)
    {
        global $neardCore;
        return Util::formatWindowsPath($neardCore->getTmpPath() . '/' . (!empty($customName) ? $customName . '-' : '') . Util::random() . $ext);
    }
    
}
