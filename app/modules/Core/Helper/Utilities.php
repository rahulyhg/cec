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
}
