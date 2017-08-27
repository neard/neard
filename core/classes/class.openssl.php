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
        $extension = 'SAN';
        $exe = '"' . $neardCore->getOpenSslExe() . '"';
        
        // ext
        $extContent = PHP_EOL . '[' . $extension . ']' . PHP_EOL;
        $extContent .= 'subjectAltName=DNS:*.' . $name . ',DNS:' . $name . PHP_EOL;
        
        // tmp openssl.cfg
        $conf = $neardCore->getTmpPath() . '/openssl_' . $name . '_' . Util::random() . '.cfg';
        file_put_contents($conf, file_get_contents($neardCore->getOpenSslConf()) . $extContent);

        // ppk
        $batch = $exe . ' genrsa -des3 -passout ' . $password . ' -out ' . $ppkPath . ' 2048 -noout -config ' . $conf. PHP_EOL;
        $batch .= 'IF %ERRORLEVEL% GEQ 1 GOTO EOF' . PHP_EOL . PHP_EOL;

        // pub
        $batch .= $exe . ' rsa -in ' . $ppkPath . ' -passin ' . $password . ' -out ' . $pubPath . PHP_EOL . PHP_EOL;
        $batch .= 'IF %ERRORLEVEL% GEQ 1 GOTO EOF' . PHP_EOL . PHP_EOL;

        // crt
        $batch .= $exe . ' req -x509 -nodes -sha256 -new -key ' . $pubPath . ' -out ' . $crtPath . ' -passin ' . $password;
        $batch .= ' -subj ' . $subject . ' -reqexts ' . $extension . ' -extensions ' . $extension . ' -config ' . $conf. PHP_EOL;
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
