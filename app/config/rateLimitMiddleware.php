<?php

namespace app\config;

class rateLimitMiddleware {
    private $rateLimiter;
    
    public function __construct() {
        $this->rateLimiter = new rateLimiter();
    }
    
    public function handle() {
        $ip = $this->getClientIp();
        $route = $_SERVER['REQUEST_URI'];
        $key = $this->generateKey($ip, $route);
        
        $maxAttempts = $this->getMaxAttemptsByRoute($route);
        
        if ($this->rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            header('X-RateLimit-Limit: ' . $maxAttempts);
            header('X-RateLimit-Remaining: 0');
            header('X-RateLimit-Reset: ' . $this->rateLimiter->getResetTime($key));
            header('Retry-After: ' . $this->rateLimiter->getResetTime($key));
            
            http_response_code(429);
            echo json_encode([
                'error' => 'Demasiados intentos.',
                'message' => 'Por favor, espere antes de realizar más intentos.',
                'reintentar_despues' => $this->rateLimiter->getResetTime($key)
            ]);
            exit;
        }
        
        $this->rateLimiter->hit($key);
        
        header('X-RateLimit-Limit: ' . $maxAttempts);
        header('X-RateLimit-Remaining: ' . $this->rateLimiter->getRemainingAttempts($key));
        header('X-RateLimit-Reset: ' . $this->rateLimiter->getResetTime($key));
    }
    
    private function getClientIp() {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (array_key_exists($header, $_SERVER)) {
                foreach (explode(',', $_SERVER[$header]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    private function generateKey($ip, $route) {
        return 'rate_limit:' . md5($ip . ':' . $route);
    }
    
    private function getMaxAttemptsByRoute($route) {
        $sensitiveRoutes = [
            '/login' => 5,         
            '/register' => 5,   
            '/reset-password' => 3 
        ];
        
        foreach ($sensitiveRoutes as $pattern => $limit) {
            if (strpos($route, $pattern) !== false) {
                return $limit;
            }
        }
        
        return 100; 
    }
}
