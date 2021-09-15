<?php
/**
 *
 * @description config manager
 *
 * @package     Config
 *
 * @time        Tue Sep 24 08:54:41 2019
 *
 * @author      kovey
 */
namespace Kovey\Library\Config;

use Swoole\Coroutine\System;
use Kovey\Library\Exception\KoveyException;

class Manager
{
    /**
     * @description config keys
     *
     * @var Swoole\Table
     */
    private static \Swoole\Table $keys;

    /**
     * @description config values
     *
     * @var Swoole\Table
     */
    private static \Swoole\Table $values;

    /**
     * @description config path
     *
     * @var string
     */
    private static string $path;

    const CONFIG_KEY_LEN = 256;

    const CONFIG_KEY1_LEN = 512;
    
    const CONFIG_KEY2_LEN = 1024;

    const CONFIG_KEY3_LEN = 1024;

    /** 
     * @description initial
     *
     * @param string $param
     *
     * @param int $maxRows = 1024
     *
     * @return void
     */
    public static function init(string $path, int $maxRows = 1024) : void
    {
        self::$path = $path;
        self::$keys = new \Swoole\Table($maxRows);
        self::$keys->column('k', \Swoole\Table::TYPE_STRING, self::CONFIG_KEY_LEN);
        self::$keys->column('k1', \Swoole\Table::TYPE_STRING, self::CONFIG_KEY1_LEN);
        self::$keys->column('k2', \Swoole\Table::TYPE_STRING, self::CONFIG_KEY2_LEN);
        self::$keys->column('k3', \Swoole\Table::TYPE_STRING, self::CONFIG_KEY3_LEN);

        self::$keys->create();
        self::$values = new \Swoole\Table($maxRows);
        self::$values->column('v', \Swoole\Table::TYPE_STRING, 512);
        self::$values->create();
        self::initParse();
    }

    /**
     * @description parse config from file
     *
     * @return void
     */
    private static function initParse() : void
    {
        $files = scandir(self::$path);
        foreach ($files as $file) {
            $suffix = substr($file, -3);
            if ($suffix === false || strtolower($suffix) !== 'ini') {
                continue;
            }

            $filePath = self::$path . '/' . $file;
            $content = file_get_contents($filePath);
            if (!$content) {
                continue;
            }

            self::writeIntoMemory(str_replace('.ini', '', $file), $content);
        }
    }

    /**
     * @description parse configs from file
     *
     * @return void
     */
    public static function parse() : void
    {
        go (function () {
            $files = scandir(self::$path);
            foreach ($files as $file) {
                $suffix = substr($file, -3);
                if ($suffix === false || strtolower($suffix) !== 'ini') {
                    continue;
                }

                $filePath = self::$path . '/' . $file;
                $content = System::readFile($filePath);
                if (!$content) {
                    continue;
                }

                self::writeIntoMemory(str_replace('.ini', '', $file), $content);
            }
        });
    }

    /**
     * @description write config into memory
     *
     * @param string $file
     *
     * @param string $content
     *
     * @return void
     */
    private static function writeIntoMemory(string $file, string $content) : void
    {
        $contents = explode("\n", $content);
        $areaKey = '';
        $areaKeys = array();
        foreach ($contents as $oneLine) {
            $oneLine = trim($oneLine);
            $first = substr($oneLine, 0, 1);
            if ($first === ';'
                || $first === '#'
            ) {
                continue;
            }

            // area begin
            if (preg_match('/\[/', $oneLine, $match)) {
                $areaKey = str_replace(array('[',']'), '', $oneLine);
                continue;
            }

            $info = explode('=', $oneLine);
            if (count($info) < 2) {
                continue;
            }

            $key = trim($info[0]);
            if ($key === '') {
                continue;
            }

            $val = trim(self::getValue($info, '=', 1), '"');
            $finalKey = $file . '.' . $areaKey . '.' . $key;
            self::$values->set(md5($finalKey), array('v' => $val));
            $areaKeys[] = $finalKey;
        }

        self::writeKeyIntoMemory('', $areaKeys);
    }

    /**
     * @description get config value
     *
     * @param Array $info
     *
     * @param string $split
     *
     * @param int $index
     *
     * @return string
     */
    private static function getValue(Array $info, string $split, int $index) : string
    {
        $len = count($info);
        if ($len == $index + 1) {
            return trim($info[$index]);
        }

        $result = '';
        for ($i = $index; $i < $len - 1; $i ++) {
            $result .= $info[$i] . $split;
        }

        return trim($result . $info[$len - 1]);
    }

    /**
     * @description write keys into memory
     *
     * @param string $pref
     *
     * @param Array $areaKeys
     *
     * @return void
     */
    private static function writeKeyIntoMemory(string $pref, Array $areaKeys) : void
    {
        $keys = array();
        $areaKey = '';
        foreach ($areaKeys as $subKey) {
            $info = explode('.', $subKey);
            if (count($info) < 2) {
                continue;
            }

            $areaKey = $info[0];
            $key = $info[1];
            if (!isset($keys[$key])) {
                $keys[$key] = array();
            }

            if (count($info) > 2) {
                $keys[$key][] = self::getValue($info, '.', 1);
            }
        }
        $areaKey = $pref === '' ? $areaKey : $pref . '.' . $areaKey;
        $keysSerial = serialize(array_keys($keys));
        $keyLen = strlen($keysSerial);
        if ($keyLen <= self::CONFIG_KEY_LEN) {
            self::$keys->set(md5($areaKey), array(
                'k' => $keysSerial, 'k1' => '', 'k2' => '', 'k3' => ''
            ));
        } else if ($keyLen <= (self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN)) {
            self::$keys->set(md5($areaKey), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, $keyLen - self::CONFIG_KEY_LEN),
                'k2' => '', 'k3' => ''
            ));
        } else if ($keyLen <= (self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN + self::CONFIG_KEY2_LEN)) {
            $start = self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN;
            self::$keys->set(md5($areaKey), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, self::CONFIG_KEY1_LEN),
                'k2' => substr($keysSerial, $start, $keyLen - $start),
                'k3' => ''
            ));
        } else {
            $start = self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN;
            $start3 = $start + self::CONFIG_KEY2_LEN;
            self::$keys->set(md5($areaKey), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, self::CONFIG_KEY1_LEN),
                'k2' => substr($keysSerial, $start, self::CONFIG_KEY2_LEN),
                'k3' => substr($keysSerial, $start3, $keyLen - $start3)
            ));
        }

        foreach ($keys as $key => $val) {
            if (count($val) > 0) {
                self::writeKeyIntoMemory($areaKey, $val);
            }
        }
    }

    /**
     * @description get config value
     *
     * @param string $key
     *
     * @return string : Array
     *
     * @throws KoveyException
     */
    public static function get(string $key) : string | Array
    {
        $val = self::$values->get(md5($key));
        if ($val !== false) {
            return $val['v'];
        }

        $kitem = self::$keys->get(md5($key));
        if ($kitem === false) {
            throw new KoveyException("$key is not exists");
        }

        $keys = unserialize($kitem['k'] . $kitem['k1'] . $kitem['k2'] . $kitem['k3']);
        $vals = array();
        foreach ($keys as $k) {
            $result = self::get($key . '.' . $k);
            if ($result === false) {
                continue;
            }

            $vals[$k] = $result;
        }

        return $vals;
    }
}
