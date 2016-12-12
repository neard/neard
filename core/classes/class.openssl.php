<?php

class OpenSsl
{
    public function createCrt($name, $destPath = null)
    {
        global $neardBs, $neardCore;
        $destPath = empty($destPath) ? $neardBs->getSslPath() : $destPath;

        $subject = '"/C=FR/O=neard/CN=' . $name . '"';
        $password = 'pass:neard';
        $ppkPath = '"' . $destPath . '/' . $name . '.ppk"';
        $pubPath = '"' . $destPath . '/' . $name . '.pub"';
        $crtPath = '"' . $destPath . '/' . $name . '.crt"';
        $exe = '"' . $neardCore->getOpenSslExe() . '"';
        $conf = '"' . $neardCore->getOpenSslConf() . '"';

        $batch = $exe . ' genrsa -des3 -passout ' . $password . ' -out ' . $ppkPath . ' 2048 -noout -config ' . $conf . PHP_EOL;
        $batch .= 'IF %ERRORLEVEL% GEQ 1 GOTO EOF' . PHP_EOL . PHP_EOL;

        $batch .= $exe . ' rsa -in ' . $ppkPath . ' -passin ' . $password . ' -out ' . $pubPath . PHP_EOL . PHP_EOL;
        $batch .= 'IF %ERRORLEVEL% GEQ 1 GOTO EOF' . PHP_EOL . PHP_EOL;

        $batch .= $exe . ' req -x509 -nodes -sha256 -new -key ' . $pubPath . ' -out ' . $crtPath . ' -passin ' . $password . ' -subj ' . $subject . ' -config ' . $conf . PHP_EOL;
        $batch .= 'IF %ERRORLEVEL% GEQ 1 GOTO EOF' . PHP_EOL . PHP_EOL;

        $batch .= ':EOF' . PHP_EOL;
        $batch .= 'SET RESULT=KO' . PHP_EOL;
        $batch .= 'IF EXIST ' . $pubPath . ' IF EXIST ' . $crtPath . ' SET RESULT=OK' . PHP_EOL;
        $batch .= 'ECHO %RESULT%';

        $result = Batch::exec('createCertificate', $batch);
        return isset($result[0]) && $result[0] == 'OK';
    }

    public function existsCrt($name)
    {
        global $neardBs;

        $ppkPath = $neardBs->getSslPath() . '/' . $name . '.ppk';
        $pubPath = $neardBs->getSslPath() . '/' . $name . '.pub';
        $crtPath = $neardBs->getSslPath() . '/' . $name . '.crt';

        return is_file($ppkPath) && is_file($pubPath) && is_file($crtPath);
    }

    public function removeCrt($name)
    {
        global $neardBs;

        $ppkPath = $neardBs->getSslPath() . '/' . $name . '.ppk';
        $pubPath = $neardBs->getSslPath() . '/' . $name . '.pub';
        $crtPath = $neardBs->getSslPath() . '/' . $name . '.crt';

        return @unlink($ppkPath) && @unlink($pubPath) && @unlink($crtPath);
    }
}
