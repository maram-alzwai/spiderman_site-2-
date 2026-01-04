<?php
class LanguageHandler {
    private static $instance = null;
    private $language = 'en';
    private $translations = [];
    
    private function __construct() {
        session_start();
        
        
        if (isset($_SESSION['language'])) {
            $this->language = $_SESSION['language'];
        } elseif (isset($_COOKIE['language'])) {
            $this->language = $_COOKIE['language'];
            $_SESSION['language'] = $this->language;
        }
        
        
        $this->loadLanguageFile();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new LanguageHandler();
        }
        return self::$instance;
    }
    
    private function loadLanguageFile() {
        $langFile = "languages/{$this->language}.php";
        
        if (file_exists($langFile)) {
            $this->translations = require $langFile;
        } else {
            
            $this->translations = require "languages/en.php";
        }
    }
    
    public function setLanguage($lang) {
        $allowedLangs = ['en', 'ar', 'es', 'fr'];
        
        if (in_array($lang, $allowedLangs)) {
            $this->language = $lang;
            $_SESSION['language'] = $lang;
            setcookie('language', $lang, time() + (365 * 24 * 60 * 60), '/');
            
            
            $this->loadLanguageFile();
            
            return true;
        }
        
        return false;
    }
    
    public function getLanguage() {
        return $this->language;
    }
    
    public function getDirection() {
        return $this->language === 'ar' ? 'rtl' : 'ltr';
    }
    
    public function translate($key, $params = []) {
        if (isset($this->translations[$key])) {
            $text = $this->translations[$key];
            
            
            foreach ($params as $param => $value) {
                $text = str_replace("{{$param}}", $value, $text);
            }
            
            return $text;
        }
        
        return $key; 
    }
    
    
    public static function trans($key, $params = []) {
        return self::getInstance()->translate($key, $params);
    }
}
?>