<?php
namespace Src;

use Symfony\Component\Console\Output\OutputInterface;

class WpPot {

    /**
     * @param string $dir
     * @param OutputInterface $output
     * @return array
     */
    function readPhpFiles($dir, $output) {
        $phpFiles = array();
        $d = dir($dir);

        if($d) {
            while (($item = $d->read()) !== false) {
                if (preg_match('/^\./', $item)) {
                    continue;
                }

                if(is_dir($dir . DIRECTORY_SEPARATOR . $item)){
                    $phpFiles = array_merge($phpFiles, $this->readPhpFiles($dir . DIRECTORY_SEPARATOR . $item, $output));
                    continue;
                }

                if (preg_match('/(.+)\.php/', $item) && is_file($dir . DIRECTORY_SEPARATOR . $item)) {
                    $filepath = $dir . DIRECTORY_SEPARATOR . $item;

                    $phpFiles[] = array(
                        'filename' => $item,
                        'filepath' => $dir . DIRECTORY_SEPARATOR . $item
                    );

                    $output->writeln(sprintf("- %s", $filepath));
                }
            }

            $d->close();
        }

        return $phpFiles;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     * @param OutputInterface $output
     * @return array
     */
    function readMsg($filePath, $fileName, $output) {
        $fp = fopen($filePath, 'r');

        $arMsgId = array();

        $output->writeln(sprintf("### Parsing %s:", $filePath));

        if ($fp) {
            $lineNum = 1;
            while (($line = fgets($fp)) !== false) {
                if(preg_match_all('/(esc_attr|)_(_|e|x)\(( *?)(\'|")(.+?)(\'|")( *)(\)|,)/', $line, $matches)) {
                    foreach ($matches[5] as $msgid){
                        $arMsgId[$msgid][] = sprintf("%s:%s", $fileName, $lineNum);
                        $output->writeln(sprintf("- Found at line %s: {%s}", $lineNum, $msgid));
                    }
                }

                $lineNum ++;
            }

            fclose($fp);
        }

        return $arMsgId;
    }

    /**
     * @param string $potName
     * @param string $themeDir
     * @param OutputInterface $output
     */
    function build($potName, $themeDir, $output){
        $arMsgId = array();

        $output->writeln("### PHP Files found:");

        $phpFiles = $this->readPhpFiles($themeDir, $output);

        if($phpFiles){
            foreach ($phpFiles as $file){
                $arMsgId = array_merge($arMsgId, $this->readMsg($file['filepath'], $file['filename'], $output));
            }
        }

        $languageDir = $themeDir . DIRECTORY_SEPARATOR . 'languages';

        $output->writeln(sprintf("### Building POT file on %s", $languageDir));

        if(!is_dir($languageDir)){
            mkdir($languageDir);
        }

        $fp = fopen($languageDir . DIRECTORY_SEPARATOR . $potName, 'w+');

        fwrite($fp, "msgid \"\"\nmsgstr \"\"\n");
        fwrite($fp, '"Content-Type: text/plain; charset=UTF-8\n"');
        fwrite($fp, "\n\n");

        if($arMsgId){
            foreach ($arMsgId as $msgid => $files){
                $msgid = str_replace('"', '\"', $msgid);
                fwrite($fp, sprintf("#: %s\nmsgid \"%s\"\nmsgstr \"\"\n\n", implode(' ', $files), $msgid));
            }
        }

        fclose($fp);
    }
}
