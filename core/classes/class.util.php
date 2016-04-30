<?php

class Util
{
    const LOG_ERROR = 'ERROR';
    const LOG_WARNING = 'WARNING';
    const LOG_INFO = 'INFO';
    const LOG_DEBUG = 'DEBUG';
    const LOG_TRACE = 'TRACE';
    
    public static function cleanArgv($name, $type = 'text')
    {
        if (isset($_SERVER['argv'])) {
            if ($type == 'text') {
                return (isset($_SERVER['argv'][$name]) && !empty($_SERVER['argv'][$name])) ? trim($_SERVER['argv'][$name]) : '';
            } elseif ($type == 'numeric') {
                return (isset($_SERVER['argv'][$name]) && is_numeric($_SERVER['argv'][$name])) ? intval($_SERVER['argv'][$name]) : '';
            } elseif ($type == 'boolean') {
                return (isset($_SERVER['argv'][$name])) ? true : false;
            } elseif ($type == 'array') {
                return (isset($_SERVER['argv'][$name]) && is_array($_SERVER['argv'][$name])) ? $_SERVER['argv'][$name] : array();
            }
        }
        
        return false;
    }
    
    public static function cleanGetVar($name, $type = 'text')
    {
        if (is_string($name)) {
            if ($type == 'text') {
                return (isset($_GET[$name]) && !empty($_GET[$name])) ? stripslashes($_GET[$name]) : '';
            } elseif ($type == 'numeric') {
                return (isset($_GET[$name]) && is_numeric($_GET[$name])) ? intval($_GET[$name]) : '';
            } elseif ($type == 'boolean') {
                return (isset($_GET[$name])) ? true : false;
            } elseif ($type == 'array') {
                return (isset($_GET[$name]) && is_array($_GET[$name])) ? $_GET[$name] : array();
            }
        }
    
        return false;
    }
    
    public static function cleanPostVar($name, $type = 'text')
    {
        if (is_string($name)) {
            if ($type == 'text') {
                return (isset($_POST[$name]) && !empty($_POST[$name])) ? stripslashes(trim($_POST[$name])) : '';
            } elseif ($type == 'number') {
                return (isset($_POST[$name]) && is_numeric($_POST[$name])) ? intval($_POST[$name]) : '';
            } elseif ($type == 'float') {
                return (isset($_POST[$name]) && is_numeric($_POST[$name])) ? floatval($_POST[$name]) : '';
            } elseif ($type == 'boolean') {
                return (isset($_POST[$name])) ? true : false;
            } elseif ($type == 'array') {
                return (isset($_POST[$name]) && is_array($_POST[$name])) ? $_POST[$name] : array();
            } elseif ($type == 'content') {
                return (isset($_POST[$name]) && !empty($_POST[$name])) ? trim($_POST[$name]) : '';
            }
        }
        
        return false;
    }
    
    public static function contains($string, $search)
    {
        if (!empty($string) && !empty($search)) {
            $result = stripos($string, $search);
            if ($result !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function startWith($string, $search)
    {
        $length = strlen($search);
        return (substr($string, 0, $length) === $search);
    }
    
    public static function endWith($string, $search)
    {
        $length = strlen($search);
        $start  = $length * -1;
        return (substr($string, $start) === $search);
    }
    
    public static function random($length = 32, $withNumeric = true)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($withNumeric){
            $characters .= '0123456789';
        }
        
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
    
    public static function clearFolders($paths, $exclude = array())
    {
        $result = array();
        foreach ($paths as $path) {
            $result[$path] = self::clearFolder($path, $exclude);
        }
    
        return $result;
    }
    
    public static function clearFolder($path, $exclude = array())
    {
        $result = array();
        $result['return'] = true;
        $result['nb_files'] = 0;
    
        $handle = @opendir($path);
        if (!$handle) {
            return;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..' || in_array($file, $exclude)) {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $r = self::clearFolder($path . '/' . $file);
                if (!$r) {
                    $result['return'] = false;
                    return $result;
                }
            } else {
                $r = @unlink($path . '/' . $file);
                if ($r) {
                    $result['nb_files']++;
                } else {
                    $result['return'] = false;
                    return $result;
                }
            }
        }
        
        closedir($handle);
    
        return $result;
    }
    
    public static function deleteFolder($path) {
        if (is_dir($path)) {
            if (substr($path, strlen($path) - 1, 1) != '/') {
                $path .= '/';
            }
            $files = glob($path . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteFolder($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($path);
        }
    }
    
    public static function findFile($startPath, $findFile)
    {
        $result = false;
        
        $handle = @opendir($startPath);
        if (!$handle) {
            return false;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $file)) {
                $result = self::findFile($startPath . '/' . $file, $findFile);
                if ($result !== false) {
                    break;
                }
            } elseif ($file == $findFile) {
                $result = self::formatUnixPath($startPath . '/' . $file);
                break;
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function findFiles($startPath, $findFiles = array(''))
    {
        $result = array();
    
        $handle = @opendir($startPath);
        if (!$handle) {
            return $result;
        }
        
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $file)) {
                $tmpResults = self::findFiles($startPath . '/' . $file, $findFiles);
                foreach($tmpResults as $tmpResult) {
                    $result[] = $tmpResult;
                }
            } elseif (is_file($startPath . '/' . $file)) {
                foreach ($findFiles as $findFile) {
                    if (self::endWith($file, $findFile) || $file == $findFile || empty($findFile)) {
                        $result[] = self::formatUnixPath($startPath . '/' . $file);
                    }
                }
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function isValidIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
    
    public static function isValidPort($port)
    {
        return is_numeric($port) && ($port > 0 || $port <= 65535);
    }
    
    public static function replaceDefine($path, $var, $value)
    {
        self::replaceInFile($path, array(
            '/^define\((.*?)' . $var . '(.*?),/' => 'define(\'' . $var . '\', ' . (is_int($value) ? $value : '\'' . $value . '\'') . ');'
        ));
    }
    
    public static function replaceInFile($path, $replaceList)
    {
        if (file_exists($path)) {
            $lines = file($path);
            $fp = fopen($path, 'w');
            foreach ($lines as $nb => $line) {
                $replaceDone = false;
                foreach ($replaceList as $regex => $replace) {
                    if (preg_match($regex, $line, $matches)) {
                        $countParams = preg_match_all('/{{(\d+)}}/', $replace, $paramsMatches);
                        if ($countParams > 0 && $countParams <= count($matches)) {
                            foreach ($paramsMatches[1] as $paramsMatch) {
                                $replace = str_replace('{{' . $paramsMatch . '}}', $matches[$paramsMatch], $replace);
                            }
                        }
                        self::logTrace('Replace in file ' . $path . ' :');
                        self::logTrace('## line_num: ' . trim($nb));
                        self::logTrace('## old: ' . trim($line));
                        self::logTrace('## new: ' . trim($replace));
                        fwrite($fp, $replace . PHP_EOL);
                        
                        $replaceDone = true;
                        break;
                    }
                }
                if (!$replaceDone) {
                    fwrite($fp, $line);
                }
            }
            fclose($fp);
        }
    }
    
    public static function getVersionList($path)
    {
        $result = array();
        
        $handle = @opendir($path);
        if (!$handle) {
            return false;
        }
        
        while (false !== ($file = readdir($handle))) {
            $filePath = $path . '/' . $file;
            if ($file != "." && $file != ".." && is_dir($filePath)) {
                $result[] = str_replace(basename($path), '', $file);
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function getMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    public static function getAppBinsRegKey($fromRegistry = true)
    {
        global $neardRegistry;
        
        if ($fromRegistry) {
            $value = $neardRegistry->getValue(
                Registry::HKEY_LOCAL_MACHINE,
                Registry::ENV_KEY,
                Registry::APP_BINS_REG_ENTRY
            );
            self::logDebug('App reg key from registry: ' . $value);
        } else {
            global $neardBins, $neardTools;
            $value = $neardBins->getApache()->getCurrentPath() . '/bin;';
            $value .= $neardBins->getPhp()->getCurrentPath() . ';';
            $value .= $neardBins->getPhp()->getPearPath() . ';';
            $value .= $neardBins->getPhp()->getImagickPath() . ';';
            $value .= $neardBins->getNodejs()->getCurrentPath() . ';';
            $value .= $neardTools->getComposer()->getCurrentPath() . ';';
            $value .= $neardTools->getDrush()->getCurrentPath() . ';';
            $value .= $neardTools->getGit()->getCurrentPath() . '/bin;';
            $value .= $neardTools->getImageMagick()->getCurrentPath() . ';';
            $value .= $neardTools->getPhpUnit()->getCurrentPath() . ';';
            $value .= $neardTools->getSvn()->getCurrentPath() . '/bin;';
            $value .= $neardTools->getWpCli()->getCurrentPath() . ';';
            $value = self::formatWindowsPath($value);
            self::logDebug('Generated app reg key: ' . $value);
        }
        
        return $value;
    }
    
    public static function setAppBinsRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::ENV_KEY,
            Registry::APP_BINS_REG_ENTRY,
            $value
        );
    }
    
    public static function getAppPathRegKey()
    {
        global $neardRegistry;
        return $neardRegistry->getValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::ENV_KEY,
            Registry::APP_PATH_REG_ENTRY
        );
    }
    
    public static function setAppPathRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::ENV_KEY,
            Registry::APP_PATH_REG_ENTRY,
            $value
        );
    }
    
    public static function getSysPathRegKey()
    {
        global $neardRegistry;
        return $neardRegistry->getValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::ENV_KEY,
            Registry::SYSPATH_REG_ENTRY
        );
    }
    
    public static function setSysPathRegKey($value)
    {
        global $neardRegistry;
        return $neardRegistry->setExpandStringValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::ENV_KEY,
            Registry::SYSPATH_REG_ENTRY,
            $value
        );
    }
    
    public static function getProcessorRegKey()
    {
        global $neardRegistry;
        return $neardRegistry->getValue(
            Registry::HKEY_LOCAL_MACHINE,
            Registry::PROCESSOR_REG_SUBKEY,
            Registry::PROCESSOR_REG_ENTRY
        );
    }
    
    public static function getStartupLnkPath()
    {
        return Vbs::getStartupPath(APP_TITLE . '.lnk');
    }
    
    public static function isLaunchStartup()
    {
        return file_exists(self::getStartupLnkPath());
    }
    
    public static function enableLaunchStartup()
    {
        return Vbs::createShortcut(self::getStartupLnkPath());
    }
    
    public static function disableLaunchStartup()
    {
        return @unlink(self::getStartupLnkPath());
    }
    
    private static function log($data, $type, $file = null)
    {
        global $neardBs, $neardCore, $neardConfig;
        $file = $file == null ? ($type == self::LOG_ERROR ? $neardBs->getErrorLogFilePath() : $neardBs->getLogFilePath()) : $file;
        
        $verbose = array();
        $verbose[Config::VERBOSE_SIMPLE] = $type == self::LOG_ERROR || $type == self::LOG_WARNING;
        $verbose[Config::VERBOSE_REPORT] = $verbose[Config::VERBOSE_SIMPLE] || $type == self::LOG_INFO;
        $verbose[Config::VERBOSE_DEBUG] = $verbose[Config::VERBOSE_REPORT] || $type == self::LOG_DEBUG;
        $verbose[Config::VERBOSE_TRACE] = $verbose[Config::VERBOSE_DEBUG] || $type == self::LOG_TRACE;
        
        $writeLog = false;
        if ($neardConfig->getLogsVerbose() == Config::VERBOSE_SIMPLE && $verbose[Config::VERBOSE_SIMPLE]) {
            $writeLog = true;
        } elseif ($neardConfig->getLogsVerbose() == Config::VERBOSE_REPORT && $verbose[Config::VERBOSE_REPORT]) {
            $writeLog = true;
        } elseif ($neardConfig->getLogsVerbose() == Config::VERBOSE_DEBUG && $verbose[Config::VERBOSE_DEBUG]) {
            $writeLog = true;
        } elseif ($neardConfig->getLogsVerbose() == Config::VERBOSE_TRACE && $verbose[Config::VERBOSE_TRACE]) {
            $writeLog = true;
        }
        
        if ($writeLog) {
            file_put_contents(
                $file,
                '[' . date('Y-m-d H:i:s', time()) . '] # ' . APP_TITLE . ' ' . $neardCore->getAppVersion() . ' # ' . $type . ': ' . $data . PHP_EOL,
                FILE_APPEND
            );
        }
    }
    
    public static function logSeparator()
    {
        global $neardBs;
        
        $logs = array(
            $neardBs->getLogFilePath(),
            $neardBs->getErrorLogFilePath(),
            $neardBs->getServicesLogFilePath(),
            $neardBs->getRegistryLogFilePath(),
            $neardBs->getStartupLogFilePath(),
            $neardBs->getBatchLogFilePath(),
            $neardBs->getVbsLogFilePath(),
            $neardBs->getWinbinderLogFilePath(),
        );
        
        $separator = '========================================================================================' . PHP_EOL;
        foreach ($logs as $log) {
            $logContent = @file_get_contents($log);
            if ($logContent !== false && !self::endWith($logContent, $separator)) {
                file_put_contents($log, $separator, FILE_APPEND);
            }
        }
    }
    
    public static function logTrace($data, $file = null)
    {
        self::log($data, self::LOG_TRACE, $file);
    }
    
    public static function logDebug($data, $file = null)
    {
        self::log($data, self::LOG_DEBUG, $file);
    }
    
    public static function logInfo($data, $file = null)
    {
        self::log($data, self::LOG_INFO, $file);
    }
    
    public static function logWarning($data, $file = null)
    {
        self::log($data, self::LOG_WARNING, $file);
    }
    
    public static function logError($data, $file = null)
    {
        self::log($data, self::LOG_ERROR, $file);
    }
    
    public static function logInitClass($classInstance)
    {
        self::logInfo('Init ' . get_class($classInstance));
    }
    
    public static function getPowerShellPath()
    {
        if (is_dir('C:\Windows\System32\WindowsPowerShell')) {
            return self::findFile('C:\Windows\System32\WindowsPowerShell', 'powershell.exe');
        }
        return false;
    }
    
    public static function findRepos($startPath, $findFolder, $checkFileIns = null)
    {
        $result = array();
        
        $handle = @opendir($startPath);
        if (!$handle) {
            return $result;
        }
        
        while (false !== ($folder = readdir($handle))) {
            if ($folder == '.' || $folder == '..') {
                continue;
            }
            if (is_dir($startPath . '/' . $folder)) {
                if ($folder == $findFolder && (empty($checkFileIns) || file_exists($startPath . '/' . $folder . '/' . $checkFileIns))) {
                    $result[] = $startPath;
                } else {
                    $resultSub = self::findRepos($startPath . '/' . $folder, $findFolder, $checkFileIns);
                    if (!empty($resultSub)) {
                        foreach ($resultSub as $aResult) {
                            array_push($result, $aResult);
                        }
                    }
                }
            }
        }
        
        closedir($handle);
        return $result;
    }
    
    public static function formatWindowsPath($path)
    {
        return str_replace('/', '\\', $path);
    }
    
    public static function formatUnixPath($path)
    {
        return str_replace('\\', '/', $path);
    }
    
    public static function imgToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    public static function utf8ToCp1252($data)
    {
        return iconv("UTF-8", "WINDOWS-1252//IGNORE", $data);
    }
    
    public static function cp1252ToUtf8($data)
    {
        return iconv("WINDOWS-1252", "UTF-8//IGNORE", $data);
    }
    
    public static function startLoading()
    {
        global $neardCore, $neardWinbinder;
        $neardWinbinder->exec($neardCore->getPhpExe(), Core::BOOTSTRAP_FILE . ' ' . Action::LOADING);
    }
    
    public static function stopLoading()
    {
        global $neardCore;
        if (file_exists($neardCore->getLoadingPid())) {
            $pids = file($neardCore->getLoadingPid());
            foreach ($pids as $pid) {
                Win32Ps::kill($pid);
            }
            @unlink($neardCore->getLoadingPid());
        }
    }
    
    public static function getPathsToScan()
    {
        global $neardBs, $neardCore, $neardBins, $neardApps, $neardTools;
        return array(
            $neardBs->getAliasPath()                        => array(''),
            $neardBs->getVhostsPath()                       => array(''),
            $neardBs->getSslPath()                          => array('.cnf'),
            $neardBins->getApache()->getRootPath()          => array('.ini', '.conf'),
            $neardBins->getPhp()->getRootPath()             => array('.php', '.bat', '.ini', '.reg', '.inc'),
            $neardBins->getMysql()->getRootPath()           => array('my.ini'),
            $neardBins->getMariadb()->getRootPath()         => array('my.ini'),
            $neardBins->getNodejs()->getRootPath()          => array('.bat', 'npmrc'),
            $neardBins->getFilezilla()->getRootPath()       => array('.xml'),
            $neardApps->getWebsvn()->getRootPath()          => array('config.php'),
            $neardApps->getGitlist()->getRootPath()         => array('config.ini'),
            $neardTools->getConsole()->getRootPath()        => array('console.xml', '.ini', '.btm'),
            $neardTools->getDrush()->getRootPath()          => array('drush.bat'),
            $neardTools->getWpCli()->getRootPath()          => array('wp.bat'),
            $neardCore->getResourcesPath() . '/homepage'    => array('.conf'),
        );
    }
    
    public static function getFilesToScan($path = null)
    {
        $result = array();
        $pathsToScan = !empty($path) ? $path : self::getPathsToScan();
        foreach ($pathsToScan as $pathToScan => $toFind) {
            $findFiles = self::findFiles($pathToScan, $toFind);
            foreach ($findFiles as $findFile) {
                $result[] = $findFile;
            }
        }
        return $result;
    }
    
    public static function getLatestVersion()
    {
        $result = self::getRemoteFile('https://raw.githubusercontent.com/wiki/crazy-max/neard/latestVersion.md');
        if (empty($result)) {
            self::logError('Cannot retrieve latest version');
            return null;
        }
        return $result;
    }
    
    public static function getVersionUrl($version)
    {
        return APP_GITHUB_HOME . '/releases/download/v' . $version . '/neard-' . $version . '.7z';
    }
    
    public static function getLatestChangelog($markdown = false)
    {
        global $neardCore, $neardBins;
        
        if (version_compare($neardBins->getPhp()->getVersion(), '5.2.17', '>')) {
            require_once $neardCore->getLibsPath() . '/markdown/1.5.0/MarkdownInterface.php';
            require_once $neardCore->getLibsPath() . '/markdown/1.5.0/Markdown.php';
            require_once $neardCore->getLibsPath() . '/markdown/1.5.0/MarkdownExtra.php';
        } else {
            require_once $neardCore->getLibsPath() . '/markdown/1.0.2/markdown.php';
        }
        
        $content = self::getRemoteFile('https://raw.githubusercontent.com/crazy-max/neard/master/CHANGELOG.md');
        if (empty($content)) {
            self::logError('Cannot retrieve latest CHANGELOG');
            return null;
        }
        return $markdown ? Markdown(preg_replace('/^.+\n.*\n/', '', $content)) : $content;
    }
    
    public static function getLatestChangelogLink()
    {
        return APP_GITHUB_HOME . '/blob/master/CHANGELOG.md';
    }
    
    public static function getRemoteFilesize($url, $humanFileSize = true)
    {
        $size = 0;
        
        $data = get_headers($url, true);
        if (isset($data['Content-Length'])) {
            $size = intval($data['Content-Length']);
        }
        
        return $humanFileSize ? self::humanFileSize($size) : $size;
    }
    
    public static function humanFileSize($size, $unit = '')
    {
        if ((!$unit && $size >= 1 << 30) || $unit == 'GB') {
            return number_format($size / (1 << 30), 2) . 'GB';
        }
        if ((!$unit && $size >= 1 << 20) || $unit == 'MB') {
            return number_format($size / (1 << 20), 2) . 'MB';
        }
        if ((!$unit && $size >= 1 << 10) || $unit == 'KB') {
            return number_format($size / (1 << 10), 2) . 'KB';
        }
        return number_format($size) . ' bytes';
    }
    
    public static function is32BitsOs()
    {
        $processor = self::getProcessorRegKey();
        return self::contains($processor, 'x86');
    }
    
    public static function getHttpHeaders($url)
    {
        global $neardCore, $neardHomepage;
        
        $result = array();
        $pingUrl = $url . '/' . $neardHomepage->getResourcesPath() . '/ping.php';
        
        if (function_exists('curl_version')) {
            $result = self::getCurlHttpHeaders($pingUrl);
        } else {
            $result = self::getFopenHttpHeaders($pingUrl);
        }
        
        if (!empty($result)) {
            $rebuildResult = array();
            foreach ($result as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $rebuildResult[] = $row;
                }
            }
            $result = $rebuildResult;
            
            self::logDebug('getHttpHeaders:');
            foreach ($result as $header) {
                self::logDebug('-> ' . $header);
            }
        }
        
        return $result;
    }
    
    public static function getFopenHttpHeaders($url)
    {
        $result = array();
        
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        ));
        
        $fp = @fopen($url, 'r', false, $context);
        if ($fp) {
            $meta = stream_get_meta_data($fp);
            $result = isset($meta['wrapper_data']) ? $meta['wrapper_data'] : $result;
            fclose($fp);
        }
        
        return $result;
    }
    
    public static function getCurlHttpHeaders($url)
    {
        $result = array();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = @curl_exec($ch);
        if (empty($response)) {
            return $result;
        }

        list($headerStr, $bodyStr) = explode("\r\n\r\n", $response, 2);
        if (empty($headerStr)) {
            return $result;
        }
        
        return explode("\n", $headerStr);
    }
    
    public static function getHeaders($host, $port, $ssl = false)
    {
        global $neardBs;
        
        $result = array();
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        ));
        
        $fp = @stream_socket_client(($ssl ? 'ssl://' : '') . $host . ':' . $port, $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);
        if ($fp) {
            $out = fgets($fp);
            $result = explode(PHP_EOL, $out);
        }
        @fclose($fp);
        
        if (!empty($result)) {
            $rebuildResult = array();
            foreach ($result as $row) {
                $row = trim($row);
                if (!empty($row)) {
                    $rebuildResult[] = $row;
                }
            }
            $result = $rebuildResult;
            
            self::logDebug('getHeaders:');
            foreach ($result as $header) {
                self::logDebug('-> ' . $header);
            }
        }
        
        return $result;
    }
    
    public static function getRemoteFile($url)
    {
        /*return @file_get_contents($url, false, stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        )));*/
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return @curl_exec($ch);
    }
    
    public static function isPortInUse($port)
    {
        $connection = @fsockopen('127.0.0.1', $port);
        if (is_resource($connection)) {
            fclose($connection);
            $process = Batch::getProcessUsingPort($port);
            return $process != null ? $process : 'N/A';
        }
        return false;
    }
    
    public static function isValidDomainName($domainName)
    {
        return preg_match('/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i', $domainName)
            && preg_match('/^.{1,253}$/', $domainName)
            && preg_match('/^[^\.]{1,63}(\.[^\.]{1,63})*$/', $domainName);
    }
    
    public static function isAlphanumeric($string)
    {
        return ctype_alnum($string);
    }
}
