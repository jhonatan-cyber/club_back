<?php

namespace app\config;

class cache {
    private static $cacheDir = __DIR__ . '/../../cache/';
    private static $defaultExpiry = 3600;

    public static function init() {
        if (!file_exists(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }

    public static function set($key, $data, $expiry = null) {
        self::init();
        $expiry = $expiry ?? self::$defaultExpiry;
        
        $cacheData = [
            'data' => $data,
            'expiry' => time() + $expiry
        ];

        $filename = self::getFileName($key);
        return file_put_contents($filename, serialize($cacheData)) !== false;
    }

    public static function get($key) {
        self::init();
        $filename = self::getFileName($key);

        if (!file_exists($filename)) {
            return null;
        }

        $cacheData = unserialize(file_get_contents($filename));
        
        if ($cacheData['expiry'] < time()) {
            self::delete($key);
            return null;
        }

        return $cacheData['data'];
    }

    public static function delete($key) {
        $filename = self::getFileName($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public static function clear() {
        self::init();
        $files = glob(self::$cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private static function getFileName($key) {
        return self::$cacheDir . md5($key) . '.cache';
    }

    public static function setCacheDir($dir) {
        self::$cacheDir = rtrim($dir, '/') . '/';
    }
}
