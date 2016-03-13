<?php
namespace Core\Helper;

use Engine\Helper as EnHelper;
use Core\Model\Language;

/**
 * Utilities Helper class
 *
 * @category  Core
 * @author    Nguyen Duc Duy <nguyenducduy.it@gmail.com>
 * @copyright 2014-2015
 * @license   New BSD License
 * @link      http://thephalconphp.com/
 */
class Utilities extends EnHelper
{
    /**
     * Get relative path.
     *
     * @param string $from From path.
     * @param string $to   To path.
     *
     * @return string
     */
    static public function getRelativePath($from, $to)
    {
        // Some compatibility fixes for Windows paths.
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to = is_dir($to) ? rtrim($to, '\/') . '/' : $to;
        $from = str_replace('\\', '/', $from);
        $to = str_replace('\\', '/', $to);

        $from = explode('/', $from);
        $to = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // Find first non-matching dir.
            if ($dir === $to[$depth]) {
                // Ignore this directory.
                array_shift($relPath);
            } else {
                // Get number of remaining dirs to $from.
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // Add traversals up to first matching dir.
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    $relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

    public static function getCurrentUrl()
    {
        $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $currentURL .= $_SERVER["SERVER_NAME"];

        if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
            $currentURL .= ":".$_SERVER["SERVER_PORT"];
        }

        $currentURL .= $_SERVER["REQUEST_URI"];
        return base64_encode($currentURL);
    }

    public static function slug($str) {
        $coDau=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ"
        ,"ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ","ê","ù","à");
        $khongDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","e","u","a");
        $clean = mb_strtolower(str_replace($coDau,$khongDau,$str));
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        $clean = trim($clean, '-');
        return $clean;
    }

    public static function truncate($phrase, $max_words)
    {
        $phrase_array = explode(' ',$phrase);
        if(count($phrase_array) > $max_words && $max_words > 0)
            $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';

        return $phrase;
    }

    public static function getCurrentDateDirName($includeDay = true)
    {
        $dateArr = getdate();

        if ($includeDay) {
            $path = $dateArr['year'] . '/' . $dateArr['month'] . '/' . $dateArr['mday'] . '/';
        } else {
            $path = date('Y') . '/' . date('m') . '/';
        }

        return $path;
    }

    /**
    * Ham dung de convert cac ky tu co dau thanh khong dau
    * Dung tot cho cac chuc nang SEO cho browser(vi nhieu engine ko
    * hieu duoc dau tieng viet, nen can phai bo dau tieng viet di)
    *
    * @param mixed $string
    */
    public static function codau2khongdau($string = '', $alphabetOnly = false, $tolower = true)
    {

        $output =  $string;
        if ($output != '') {
            //Tien hanh xu ly bo dau o day
            $search = array(
                '&#225;', '&#224;', '&#7843;', '&#227;', '&#7841;',                 // a' a` a? a~ a.
                '&#259;', '&#7855;', '&#7857;', '&#7859;', '&#7861;', '&#7863;',    // a( a('
                '&#226;', '&#7845;', '&#7847;', '&#7849;', '&#7851;', '&#7853;',    // a^ a^'..
                '&#273;',                                                       // d-
                '&#233;', '&#232;', '&#7867;', '&#7869;', '&#7865;',                // e' e`..
                '&#234;', '&#7871;', '&#7873;', '&#7875;', '&#7877;', '&#7879;',    // e^ e^'
                '&#237;', '&#236;', '&#7881;', '&#297;', '&#7883;',                 // i' i`..
                '&#243;', '&#242;', '&#7887;', '&#245;', '&#7885;',                 // o' o`..
                '&#244;', '&#7889;', '&#7891;', '&#7893;', '&#7895;', '&#7897;',    // o^ o^'..
                '&#417;', '&#7899;', '&#7901;', '&#7903;', '&#7905;', '&#7907;',    // o* o*'..
                '&#250;', '&#249;', '&#7911;', '&#361;', '&#7909;',                 // u'..
                '&#432;', '&#7913;', '&#7915;', '&#7917;', '&#7919;', '&#7921;',    // u* u*'..
                '&#253;', '&#7923;', '&#7927;', '&#7929;', '&#7925;',               // y' y`..

                '&#193;', '&#192;', '&#7842;', '&#195;', '&#7840;',                 // A' A` A? A~ A.
                '&#258;', '&#7854;', '&#7856;', '&#7858;', '&#7860;', '&#7862;',    // A( A('..
                '&#194;', '&#7844;', '&#7846;', '&#7848;', '&#7850;', '&#7852;',    // A^ A^'..
                '&#272;',                                                           // D-
                '&#201;', '&#200;', '&#7866;', '&#7868;', '&#7864;',                // E' E`..
                '&#202;', '&#7870;', '&#7872;', '&#7874;', '&#7876;', '&#7878;',    // E^ E^'..
                '&#205;', '&#204;', '&#7880;', '&#296;', '&#7882;',                 // I' I`..
                '&#211;', '&#210;', '&#7886;', '&#213;', '&#7884;',                 // O' O`..
                '&#212;', '&#7888;', '&#7890;', '&#7892;', '&#7894;', '&#7896;',    // O^ O^'..
                '&#416;', '&#7898;', '&#7900;', '&#7902;', '&#7904;', '&#7906;',    // O* O*'..
                '&#218;', '&#217;', '&#7910;', '&#360;', '&#7908;',                 // U' U`..
                '&#431;', '&#7912;', '&#7914;', '&#7916;', '&#7918;', '&#7920;',    // U* U*'..
                '&#221;', '&#7922;', '&#7926;', '&#7928;', '&#7924;'                // Y' Y`..
            );

            $search2 = array(
                'á', 'à', 'ả', 'ã', 'ạ',                // a' a` a? a~ a.
                'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ',   // a( a('
                'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ',   // a^ a^'..
                'đ',                                                        // d-
                'é', 'è', 'ẻ', 'ẽ', 'ẹ',                // e' e`..
                'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',   // e^ e^'
                'í', 'ì', 'ỉ', 'ĩ', 'ị',                    // i' i`..
                'ó', 'ò', 'ỏ', 'õ', 'ọ',                    // o' o`..
                'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ',   // o^ o^'..
                'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',   // o* o*'..
                'ú', 'ù', 'ủ', 'ũ', 'ụ',                    // u'..
                'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',   // u* u*'..
                'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ',                // y' y`..

                'Á', 'À', 'Ả', 'Ã', 'Ạ',                    // A' A` A? A~ A.
                'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ',   // A( A('..
                'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',   // A^ A^'..
                'Đ',                                                            // D-
                'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ',                // E' E`..
                'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',   // E^ E^'..
                'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',                    // I' I`..
                'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ',                    // O' O`..
                'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ',   // O^ O^'..
                'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',   // O* O*'..
                'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ',                    // U' U`..
                'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',   // U* U*'..
                'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'             // Y' Y`..
            );

            $replace = array(
                'a', 'a', 'a', 'a', 'a',
                'a', 'a', 'a', 'a', 'a', 'a',
                'a', 'a', 'a', 'a', 'a', 'a',
                'd',
                'e', 'e', 'e', 'e', 'e',
                'e', 'e', 'e', 'e', 'e', 'e',
                'i', 'i', 'i', 'i', 'i',
                'o', 'o', 'o', 'o', 'o',
                'o', 'o', 'o', 'o', 'o', 'o',
                'o', 'o', 'o', 'o', 'o', 'o',
                'u', 'u', 'u', 'u', 'u',
                'u', 'u', 'u', 'u', 'u', 'u',
                'y', 'y', 'y', 'y', 'y',

                'A', 'A', 'A', 'A', 'A',
                'A', 'A', 'A', 'A', 'A', 'A',
                'A', 'A', 'A', 'A', 'A', 'A',
                'D',
                'E', 'E', 'E', 'E', 'E',
                'E', 'E', 'E', 'E', 'E', 'E',
                'I', 'I', 'I', 'I', 'I',
                'O', 'O', 'O', 'O', 'O',
                'O', 'O', 'O', 'O', 'O', 'O',
                'O', 'O', 'O', 'O', 'O', 'O',
                'U', 'U', 'U', 'U', 'U',
                'U', 'U', 'U', 'U', 'U', 'U',
                'Y', 'Y', 'Y', 'Y', 'Y'
            );

            //print_r($search);
            $output = str_replace($search, $replace, $output);
            $output = str_replace($search2, $replace, $output);

            if ($alphabetOnly) {
                $output = self::alphabetonly($output);
            }

            if ($tolower) {
                $output = strtolower($output);
            }
        }

        return $output;
    }

    public static function specialchar2normalchar($string = '')
    {
        $output =  $string;
        if ($output != '') {
            //Tien hanh xu ly bo dau o day
            $search = array(
                '&#225;', '&#224;', '&#7843;', '&#227;', '&#7841;',                 // a' a` a? a~ a.
                '&#259;', '&#7855;', '&#7857;', '&#7859;', '&#7861;', '&#7863;',    // a( a('
                '&#226;', '&#7845;', '&#7847;', '&#7849;', '&#7851;', '&#7853;',     // a^ a^'..
                '&#273;',                                                           // d-
                '&#233;', '&#232;', '&#7867;', '&#7869;', '&#7865;',                // e' e`..
                '&#234;', '&#7871;', '&#7873;', '&#7875;', '&#7877;', '&#7879;',    // e^ e^'
                '&#237;', '&#236;', '&#7881;', '&#297;', '&#7883;',                    // i' i`..
                '&#243;', '&#242;', '&#7887;', '&#245;', '&#7885;',                    // o' o`..
                '&#244;', '&#7889;', '&#7891;', '&#7893;', '&#7895;', '&#7897;',    // o^ o^'..
                '&#417;', '&#7899;', '&#7901;', '&#7903;', '&#7905;', '&#7907;',    // o* o*'..
                '&#250;', '&#249;', '&#7911;', '&#361;', '&#7909;',                    // u'..
                '&#432;', '&#7913;', '&#7915;', '&#7917;', '&#7919;', '&#7921;',    // u* u*'..
                '&#253;', '&#7923;', '&#7927;', '&#7929;', '&#7925;',                // y' y`..

                '&#193;', '&#192;', '&#7842;', '&#195;', '&#7840;',                    // A' A` A? A~ A.
                '&#258;', '&#7854;', '&#7856;', '&#7858;', '&#7860;', '&#7862;',    // A( A('..
                '&#194;', '&#7844;', '&#7846;', '&#7848;', '&#7850;', '&#7852;',    // A^ A^'..
                '&#272;',                                                            // D-
                '&#201;', '&#200;', '&#7866;', '&#7868;', '&#7864;',                // E' E`..
                '&#202;', '&#7870;', '&#7872;', '&#7874;', '&#7876;', '&#7878;',    // E^ E^'..
                '&#205;', '&#204;', '&#7880;', '&#296;', '&#7882;',                    // I' I`..
                '&#211;', '&#210;', '&#7886;', '&#213;', '&#7884;',                    // O' O`..
                '&#212;', '&#7888;', '&#7890;', '&#7892;', '&#7894;', '&#7896;',    // O^ O^'..
                '&#416;', '&#7898;', '&#7900;', '&#7902;', '&#7904;', '&#7906;',    // O* O*'..
                '&#218;', '&#217;', '&#7910;', '&#360;', '&#7908;',                    // U' U`..
                '&#431;', '&#7912;', '&#7914;', '&#7916;', '&#7918;', '&#7920;',    // U* U*'..
                '&#221;', '&#7922;', '&#7926;', '&#7928;', '&#7924;'                // Y' Y`..
            );

            $replace = array(
                'á', 'à', 'ả', 'ã', 'ạ',                 // a' a` a? a~ a.
                'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ',    // a( a('
                'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ',     // a^ a^'..
                'đ',                                                           // d-
                'é', 'è', 'ẻ', 'ẽ', 'ẹ',                // e' e`..
                'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',    // e^ e^'
                'í', 'ì', 'ỉ', 'ĩ', 'ị',                    // i' i`..
                'ó', 'ò', 'ỏ', 'õ', 'ọ',                    // o' o`..
                'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ',    // o^ o^'..
                'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',    // o* o*'..
                'ú', 'ù', 'ủ', 'ũ', 'ụ',                    // u'..
                'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',    // u* u*'..
                'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ',                // y' y`..

                'Á', 'À', 'Ả', 'Ã', 'Ạ',                    // A' A` A? A~ A.
                'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ',    // A( A('..
                'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',    // A^ A^'..
                'Đ',                                                            // D-
                'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ',                // E' E`..
                'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',    // E^ E^'..
                'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',                    // I' I`..
                'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ',                    // O' O`..
                'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ',    // O^ O^'..
                'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',    // O* O*'..
                'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ',                    // U' U`..
                'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',    // U* U*'..
                'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'                // Y' Y`..
            );

            //print_r($search);
            $output = str_replace($search, $replace, $output);
        }

        return $output;
    }

    public static function alphabetonly($string = '')
    {
        $output = $string;
        //replace no alphabet character
        $output = preg_replace("/[^a-zA-Z0-9]/", "-", $output);
        $output = preg_replace("/-+/", "-", $output);
        $output = trim($output, '-');

        return $output;
    }

    /**
    * Manual css filter
    *
    * @param mixed $data
    * @return mixed
    */
    public static function xssClean($data)
    {

        // Fix &entity\n;
        //$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return $data;
    }

    public static function refineMoneyString($moneyString = '')
    {
        $money = preg_replace('/[^0-9]/i', '', $moneyString);

        return (float) $money;
    }


    /**
    * Loai bo ky tu khogn can thiet de chong XSS
    * Loai bo HTML tag, chi giu lai cac ky tu binh thuong, ko format
    *
    * @param mixed $s
    */
    public static function plaintext($s)
    {
        $s = strip_tags($s);
        $s = self::xssClean($s);

        return $s;
    }

    public static function validateEmail($email)
    {
        return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
    }


    public static function validatePhone($phone) {
        return preg_match('/^[0|+]{1}[0-9]{9,11}$/is', $phone);
    }

    public static function validateUrl($url) {
        if(filter_var($url, FILTER_VALIDATE_URL) === FALSE){
            return false;
        } else{
            return true;
        }
    }

    // calculate geographical proximity
    public static function mathGeoProximity( $latitude, $longitude, $radius, $miles = false )
    {
        $radius = $miles ? $radius : ($radius * 0.621371192);

        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        return array(
            'latitudeMin'  => $lat_min,
            'latitudeMax'  => $lat_max,
            'longitudeMin' => $lng_min,
            'longitudeMax' => $lng_max
        );
    }

    /**
    * Unique ID generation.
    */
    public static function unique_id()
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * Convert YYYY-mm-dd, YYYYmmdd to timestamp
     * @param $ymdString
     * @return int
     */
    public static function ymdToTimestamp($ymdString)
    {
        $ts = 0;

        $ymdString = str_replace('-', '', $ymdString);
        if (preg_match('/(\d{4})(\d{2})(\d{2})/', $ymdString, $match)) {
            $ts = mktime(0, 0, 1, $match[2], $match[3], $match[1]);
        }

        return $ts;
    }

    /**
     * Convert YYYY-mm-dd HH:MM:SS to timestamp
     * @param $ymdhisString
     * @return int
     */
    public static function ymdhisToTimestamp($ymdhisString)
    {
        $ts = 0;

        $info = explode(' ', $ymdhisString);

        //process hour
        $timeInfo = explode(':', $info[1]);

        //process date
        $ymdString = str_replace('-', '', $info[0]);
        if (preg_match('/(\d{4})(\d{2})(\d{2})/', $ymdString, $match)) {
            $ts = mktime($timeInfo[0], $timeInfo[1], $timeInfo[2], $match[2], $match[3], $match[1]);
        }

        return $ts;
    }

    // public static function formatHumanTime($timestamp)
    // {
    //     Date::setLocale('vi');
    //     $date = new Date((int) $timestamp);
    //     return $date->ago();
    // }
}
