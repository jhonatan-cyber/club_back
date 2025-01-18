<?php

namespace app\config;

class rateLimiter {
    private $redis;
    private $maxAttempts;
    private $decayMinutes;
    
    public function __construct($maxAttempts = 60, $decayMinutes = 1) {
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
        $this->initializeStorage();
    }
    
    private function initializeStorage() {
        $storageDir = dirname(__DIR__, 2) . '/storage/framework/ratelimit';
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
    }
    
    public function tooManyAttempts($key, $maxAttempts = null) {
        $maxAttempts = $maxAttempts ?? $this->maxAttempts;
        $attempts = $this->getAttempts($key);
        
        return $attempts >= $maxAttempts;
    }
    
    public function hit($key) {
        $storageFile = $this->getStorageFile($key);
        $attempts = $this->getAttempts($key);
        $timestamp = time();
  
        $this->clearOldAttempts($key);
        
        $attempts = $this->getAttempts($key);
        $attempts++;
        
        $data = [
            'attempts' => $attempts,
            'timestamp' => $timestamp
        ];
        
        file_put_contents($storageFile, json_encode($data));
        
        return $attempts;
    }
    
    public function clear($key) {
        $storageFile = $this->getStorageFile($key);
        if (file_exists($storageFile)) {
            unlink($storageFile);
        }
    }
    
    private function getAttempts($key) {
        $storageFile = $this->getStorageFile($key);
        if (!file_exists($storageFile)) {
            return 0;
        }
        
        $data = json_decode(file_get_contents($storageFile), true);
        if (!$data) {
            return 0;
        }

        if (time() - $data['timestamp'] > $this->decayMinutes * 60) {
            $this->clear($key);
            return 0;
        }
        
        return $data['attempts'];
    }
    
    private function clearOldAttempts($key) {
        $storageFile = $this->getStorageFile($key);
        if (!file_exists($storageFile)) {
            return;
        }
        
        $data = json_decode(file_get_contents($storageFile), true);
        if (!$data) {
            return;
        }
        
        if (time() - $data['timestamp'] > $this->decayMinutes * 60) {
            $this->clear($key);
        }
    }
    
    private function getStorageFile($key) {
        $storageDir = dirname(__DIR__, 2) . '/storage/framework/ratelimit';
        return $storageDir . '/' . md5($key) . '.json';
    }
    
    public function getRemainingAttempts($key) {
        return $this->maxAttempts - $this->getAttempts($key);
    }
    
    public function getResetTime($key) {
        $storageFile = $this->getStorageFile($key);
        if (!file_exists($storageFile)) {
            return 0;
        }
        
        $data = json_decode(file_get_contents($storageFile), true);
        if (!$data) {
            return 0;
        }
        
        return ($data['timestamp'] + ($this->decayMinutes * 60)) - time();
    }
}
