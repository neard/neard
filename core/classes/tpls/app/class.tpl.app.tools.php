<?php

class TplAppTools
{
    const MENU = 'tools';

    const ACTION_GEN_SSL_CERTIFICATE = 'genSslCertificate';

    public static function process()
    {
        global $neardLang;

        return TplApp::getMenu($neardLang->getValue(Lang::TOOLS), self::MENU, get_called_class());
    }

    public static function getMenuTools()
    {
        global $neardLang, $neardCore, $neardTools;
        $resultItems = $resultActions = '';

        // Git
        $tplGit = TplAppGit::process();
        $resultItems .= $tplGit[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplGit[TplApp::SECTION_CONTENT] . PHP_EOL;

        // Python
        $tplPython = TplAppPython::process();
        $resultItems .= $tplPython[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplPython[TplApp::SECTION_CONTENT] . PHP_EOL;

        // Composer
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::COMPOSER),
            TplAestan::GLYPH_COMPOSER,
            $neardTools->getConsoleZ()->getTabTitleComposer()
        ) . PHP_EOL;

        // Ghostscript
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::GHOSTSCRIPT),
            TplAestan::GLYPH_GHOSTSCRIPT,
            $neardTools->getConsoleZ()->getTabTitleGhostscript()
        ) . PHP_EOL;

        // Ngrok
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::NGROK),
            TplAestan::GLYPH_NGROK,
            $neardTools->getConsoleZ()->getTabTitleNgrok()
        ) . PHP_EOL;

        // Pear
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::PEAR),
            TplAestan::GLYPH_PEAR,
            $neardTools->getConsoleZ()->getTabTitlePear()
        ) . PHP_EOL;

        // Perl
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::PERL),
            TplAestan::GLYPH_PERL,
            $neardTools->getConsoleZ()->getTabTitlePerl()
        ) . PHP_EOL;

        // PhpMetrics
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::PHPMETRICS),
            TplAestan::GLYPH_PHPMETRICS,
            $neardTools->getConsoleZ()->getTabTitlePhpMetrics()
        ) . PHP_EOL;

        // PHPUnit
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::PHPUNIT),
            TplAestan::GLYPH_PHPUNIT,
            $neardTools->getConsoleZ()->getTabTitlePhpUnit()
        ) . PHP_EOL;

        // Ruby
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::RUBY),
            TplAestan::GLYPH_RUBY,
            $neardTools->getConsoleZ()->getTabTitleRuby()
        ) . PHP_EOL;

        // WP-CLI
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::WPCLI),
            TplAestan::GLYPH_WPCLI,
            $neardTools->getConsoleZ()->getTabTitleWpCli()
        ) . PHP_EOL;

        // XDebugClient
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::XDC),
            $neardTools->getXdc()->getExe(),
            TplAestan::GLYPH_DEBUG
        ) . PHP_EOL;

        // Yarn
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::YARN),
            TplAestan::GLYPH_YARN,
            $neardTools->getConsoleZ()->getTabTitleYarn()
        ) . PHP_EOL;

        $resultItems .= TplAestan::getItemSeparator() . PHP_EOL;

        // Console
        $resultItems .= TplAestan::getItemConsoleZ(
            $neardLang->getValue(Lang::CONSOLE),
            TplAestan::GLYPH_CONSOLEZ
        ) . PHP_EOL;

        // HostsEditor
        $resultItems .= TplAestan::getItemExe(
            $neardLang->getValue(Lang::HOSTSEDITOR),
            $neardCore->getHostsEditorExe(),
            TplAestan::GLYPH_HOSTSEDITOR
        ) . PHP_EOL;

        // Generate SSL Certificate
        $tplGenSslCertificate = TplApp::getActionMulti(
            self::ACTION_GEN_SSL_CERTIFICATE, null,
            array($neardLang->getValue(Lang::MENU_GEN_SSL_CERTIFICATE), TplAestan::GLYPH_SSL_CERTIFICATE),
            false, get_called_class()
        );
        $resultItems .= $tplGenSslCertificate[TplApp::SECTION_CALL] . PHP_EOL;
        $resultActions .= $tplGenSslCertificate[TplApp::SECTION_CONTENT];

        return $resultItems . PHP_EOL . $resultActions;
    }

    public static function getActionGenSslCertificate()
    {
        return TplApp::getActionRun(Action::GEN_SSL_CERTIFICATE);
    }
}
