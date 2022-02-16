<?php
/**
 *
 * @description config manager, parse json file
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
use Kovey\Library\Util\Json;

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

    const EMPTY_VALUE = 'kovey_array_empty';

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
            $suffix = substr($file, -4);
            if ($suffix === false || strtolower($suffix) !== 'json') {
                continue;
            }

            $filePath = self::$path . '/' . $file;
            $content = file_get_contents($filePath);
            if (!$content) {
                continue;
            }

            try {
                $config = Json::decode($content);
                self::writeIntoMemory(str_replace('.json', '', $file), $config);
            } catch (\Throwable $e) {
                continue;
            }
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
                $suffix = substr($file, -4);
                if ($suffix === false || strtolower($suffix) !== 'json') {
                    continue;
                }

                $filePath = self::$path . '/' . $file;
                $content = System::readFile($filePath);
                if (!$content) {
                    continue;
                }

                try {
                    $config = Json::decode($content);
                    self::writeIntoMemory(str_replace('.json', '', $file), $config);
                } catch (\Throwable $e) {
                    continue;
                }
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
    private static function writeIntoMemory(string $file, Array $config) : void
    {
        if (empty($config)) {
            return;
        }

        self::writeKeyIntoMemory($file, array_keys($config));

        foreach ($config as $key => $configs) {
            $finalKey = $file . '.' . trim($key);
            if (!is_array($configs)) {
                self::$values->set(md5($finalKey), array('v' => $configs));
                continue;
            }

            if (empty($configs)) {
                self::$values->set(md5($finalKey), array('v' => self::EMPTY_VALUE));
                continue;
            }

            self::writeIntoMemory($finalKey, $configs);
        }
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
    private static function writeKeyIntoMemory(string $pref, Array $keys) : void
    {
        $keysSerial = serialize($keys);
        $keyLen = strlen($keysSerial);
        if ($keyLen <= self::CONFIG_KEY_LEN) {
            self::$keys->set(md5($pref), array(
                'k' => $keysSerial, 'k1' => '', 'k2' => '', 'k3' => ''
            ));
        } else if ($keyLen <= (self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN)) {
            self::$keys->set(md5($pref), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, $keyLen - self::CONFIG_KEY_LEN),
                'k2' => '', 'k3' => ''
            ));
        } else if ($keyLen <= (self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN + self::CONFIG_KEY2_LEN)) {
            $start = self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN;
            self::$keys->set(md5($pref), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, self::CONFIG_KEY1_LEN),
                'k2' => substr($keysSerial, $start, $keyLen - $start),
                'k3' => ''
            ));
        } else {
            $start = self::CONFIG_KEY_LEN + self::CONFIG_KEY1_LEN;
            $start3 = $start + self::CONFIG_KEY2_LEN;
            self::$keys->set(md5($pref), array(
                'k' => substr($keysSerial, 0, self::CONFIG_KEY_LEN),
                'k1' => substr($keysSerial, self::CONFIG_KEY_LEN, self::CONFIG_KEY1_LEN),
                'k2' => substr($keysSerial, $start, self::CONFIG_KEY2_LEN),
                'k3' => substr($keysSerial, $start3, $keyLen - $start3)
            ));
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
            return $val['v'] === self::EMPTY_VALUE ? array() : $val['v'];
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
