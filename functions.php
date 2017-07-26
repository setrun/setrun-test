<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

use yii\base\InvalidParamException;

/**
 * Find application by domain name
 */
function findApplicationByDomain() : void
{
    $default = $app = 'master';

    $file    = __DIR__ . '/config/domains.php';
    $domains = file_exists($file) ? (array) require $file : [];
    $dir     = __DIR__ . '/applications/';

    foreach ($domains as  $key => $value) {
        if ($_SERVER['HTTP_HOST'] === $key) {
            $app = $value;
            break;
        }
    }
    if (!is_dir($dir . $app)) {
        $app = $default;
    }
    define('APP_NAME', $app);
    define('APP_PATH', $dir . $app);
}

/**
 * @param $key
 * @param null $value
 * @param null $default
 * @return mixed
 */
function alias($key, $value = null, $default = null) : mixed
{
    try {
        if ($value !== null) return Yii::setAlias($key, $value);
        return Yii::getAlias($key);
    } catch (InvalidParamException $e) {
        return $default;
    }
}

/**
 * Debug
 * @param  array ...$params List of parameters for debug
 * @return string
 */
function debug(...$params) : string
{
    $e     = true;  // Exit true
    $v     = false; // Var dump false
    $trace = debug_backtrace(); // Get trace
    foreach ($params as $key => $value) {
        if ($value === '-e') {  // If you change the param exit
            $e = false; unset($params[$key]);
        }
        if ($value === '-v') { // If you change the param var dump
            $v = true; unset($params[$key]);
        }
    }
    echo '<br/><strong>Debug</strong>: <pre>';
    foreach ($params as $key => $value) {
        $file = $trace[0]['file'] ?? false;
        $line = $trace[0]['line'] ?? '';
        echo "<br/><span style=\"color:#CC9900\"> ============ > {$file} [{$line}]</span><br/>";
        echo '<br/><br/><br/><span style="color:#666">';
        ob_start();
        $v ? print_r(var_dump($value)) : print_r($value);
        $output = ob_get_clean();
        if ($v) $output = substr($output, strpos($output, ':')+4);
        echo $output;
        if (!$v) echo "<br/><br/><br/>";
    }
    echo '</span></pre><br/>';
    if($e) exit();
}