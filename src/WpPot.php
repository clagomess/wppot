<?php
namespace Src;

class WpPot {
    function readPhpFiles($dir) {
        $phpFiles = array();
        $d = dir($dir);

        if($d) {
            while (($item = $d->read()) !== false) {
                if (preg_match('/^\./', $item)) {
                    continue;
                }

                if(is_dir($dir . DIRECTORY_SEPARATOR . $item)){
                    $phpFiles = array_merge($phpFiles, $this->readPhpFiles($dir . DIRECTORY_SEPARATOR . $item));
                    continue;
                }

                if (preg_match('/(.+)\.php/', $item) && is_file($dir . DIRECTORY_SEPARATOR . $item)) {
                    $phpFiles[] = array(
                        'filename' => $item,
                        'filepath' => $dir . DIRECTORY_SEPARATOR . $item
                    );
                }
            }

            $d->close();
        }

        return $phpFiles;
    }

    function readMsg($filePath, $fileName) {
        $fp = fopen($filePath, 'r');

        $arMsgId = array();

        if ($fp) {
            $lineNum = 1;
            while (($line = fgets($fp)) !== false) {
                if(preg_match_all('/_(_|e)\((\'|")(.+?)(\'|")\)/', $line, $matches)) {
                    foreach ($matches[3] as $msgid){
                        $arMsgId[$msgid][] = sprintf("%s:%s", $fileName, $lineNum);
                    }
                }

                $lineNum ++;
            }

            fclose($fp);
        }

        return $arMsgId;
    }

    function build($potName, $themeDir){
        $arMsgId = array();
        $phpFiles = $this->readPhpFiles($themeDir);

        if($phpFiles){
            foreach ($phpFiles as $file){
                $arMsgId = array_merge($arMsgId, $this->readMsg($file['filepath'], $file['filename']));
            }
        }

        $languageDir = $themeDir . DIRECTORY_SEPARATOR . 'languages';

        if(!is_dir($languageDir)){
            mkdir($languageDir);
        }

        $fp = fopen($languageDir . DIRECTORY_SEPARATOR . $potName, 'w+');

        fwrite($fp, "msgid \"\"\nmsgstr \"\"\n");
        fwrite($fp, '"Content-Type: text/plain; charset=UTF-8\n"');
        fwrite($fp, "\n\n");

        if($arMsgId){
            foreach ($arMsgId as $msgid => $files){
                fwrite($fp, sprintf("#: %s\nmsgid \"%s\"\nmsgstr \"\"\n\n", implode(' ', $files), $msgid));
            }
        }

        fclose($fp);
    }
}
