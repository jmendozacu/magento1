<?php

function read_all($file, $lastFile = 'no product', $lastFile2 = 'File import is not exist or can not be readed.') {
    if (file_exists($file)) {
        $file = fopen($file, 'r');
        $data = '';
        $lastProduct = false;
        $count = 0;
        $numberLine = 0;
        while (!feof($file)) {

            $line = fgets($file);
            //echo '<u>Section</u><p>nl2br'.($line).'</p>';
            $string = trim(strtolower($line));
            if ($string == $lastFile || $string == $lastFile2) {
                $lastProduct = true;
                break;
            } else {
                $pos = strpos($line, '|');
                if ($pos !== false) {
                    $lineData = explode('|', $line);
                    if ($lineData[0] == 'I') {
                        $count++;
                    }
                    $data .= "<p>{$lineData[1]}</p>";
                }else{
                    $data .= "<p>{$line}</p>";
                }
                $lastProduct = false;
            }
            $numberLine++;
        }
        if ($lastProduct) {
            $data .= "<p>Total products: $count</p>";
        }
        fclose($file);
        return json_encode(array('text' => $data, 'lastProduct' => $lastProduct, 'countLine' => $numberLine));
    }else{
    return json_encode(array('text' => '', 'lastProduct' => FALSE, 'countLine' => '0'));
    }
}

//header('');
echo read_all(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'log_product.txt', 'no product');
?>