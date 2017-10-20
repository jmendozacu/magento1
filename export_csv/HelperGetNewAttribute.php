<?php

class GetNewAttribute {

    function renderAttribute($content) {
        $results = array();
        $attributteAdd = array('Features', 'Dimensions', 'Print Area', 'Branding Options', 'Description', 'Available Colors');
        if (!$content)
            return array();
        $arrayAttribute = explode('<h4>', $content);
        for ($i = 0; $i < count($arrayAttribute); $i++) {
            $item = $arrayAttribute[$i];
            $fined = false;
            if ($item != "") {
                $item = str_replace('</h4>', '', trim($item));
                for ($j = 0; $j < count($attributteAdd); $j++) {
                    $lenghtStringFind = 0;
                    if (($lenFindString = self::findFirstString($item, $attributteAdd[$j], $lenghtStringFind))) {

                        if ($lenFindString == 1) {
                            $start = $lenghtStringFind;
                        } else {
                            $start = $lenFindString;
                        }
                        //Loai bo dau ":"
                        $startDot = strpos($item, ':');
                        if (($startDot >= $start && $startDot <= ($start + 10))) {
                            $start = $startDot + 1;
                        }
                        $stringText = substr($item, $start); // = strip_tags(substr($item, $start));
                        if ($stringText) {
                            $stringText = strip_tags($stringText);
                            $stringText = trim(str_replace(array('.  .', '  ', '   ', '&nbsp;'), array('.', ' ', ' ', ' '), $stringText));
                            //Xu ly nhung ky tu loi con lai
                            $lenghtStringFind = 0;
                            $firstChar = self::findFirstString($stringText, '-', $lenghtStringFind);
                            if ($firstChar == 1) {
                                $stringText = trim(substr($stringText, 1));
                            }
                            $firstChar = false;
                            $firstChar = self::findFirstString($stringText, ':', $lenghtStringFind);
                            if ($firstChar == 1) {
                                $stringText = trim(substr($stringText, 1));
                            }
                            $firstChar = false;
                            $firstChar = self::findFirstString($stringText, '&amp;nbsp;', $lenghtStringFind);
                            if ($firstChar == 1) {
                                $stringText = trim(substr($stringText, $lenghtStringFind));
                            }
                        }

                        $results[$attributteAdd[$j]] = $stringText; //strip_tags(substr($item, $start));
                        $fined = true;
                        break;
                    }
                }
                if (!$fined) {
                    $stringText = $item;
                    if ($stringText) {
                        $stringText = strip_tags($stringText);
                        $stringText = trim(str_replace(array('.  .', '  ', '   ', '&nbsp;'), array('.', ' ', ' ', ' '), $stringText));
                        $lenghtStringFind = 0;
                        $firstChar = self::findFirstString($stringText, '-', $lenghtStringFind);
                        if ($firstChar == 1) {
                            $stringText = trim(substr($stringText, 1));
                        }
                        $firstChar = false;
                        $firstChar = self::findFirstString($stringText, ':', $lenghtStringFind);
                        if ($firstChar == 1) {
                            $stringText = trim(substr($stringText, 1));
                        }
                        $firstChar = false;
                        $firstChar = self::findFirstString($stringText, '&amp;nbsp;', $lenghtStringFind);
                        if ($firstChar == 1) {
                            $stringText = trim(substr($stringText, $lenghtStringFind));
                        }
                    }

                    $results['Description'] = $stringText;
                }
            }
        }
        return $results;
    }

    function findFirstString($string, $stringFind, &$lenghtStringFind) {
        if (!$string || !$stringFind)
            return false;
        $lenghtStringFind = strlen($stringFind);
        if ($stringFind == substr($string, 0, $lenghtStringFind)) {
            return 1;
        } else {
            $stringSub = substr($string, 0, $lenghtStringFind + 10);
            if (($position = strpos($stringSub, $stringFind))) {
                //can tra ve vi tri de cat chuoi
                return $position + $lenghtStringFind;
            } else {
                return false;
            }
        }
    }

}

?>