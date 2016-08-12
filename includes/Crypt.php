<?php

namespace MocaBonita\includes;

class Crypt
{
    private $iv;
    private $key;
    private static $instance;

    private function __construct($iv = "9876543210fedcba", $key = "moca_bonita")
    {
        $this->iv  = defined("NONCE_KEY") ? NONCE_KEY : $iv;
        $this->key = defined("AUTH_KEY")  ? AUTH_KEY  : $key;
    }

    /**
     * @param string $str
     * @param bool $isBinary whether to encrypt as binary or not. Default is: false
     * @return string Encrypted data
     */
    private function encryptInternal($str, $isBinary = false)
    {
        $iv = $this->iv;
        $str = $isBinary ? $str : utf8_decode($str);
        $td = mcrypt_module_open('rijndael-128', ' ', 'cbc', $iv);
        mcrypt_generic_init($td, $this->key, $iv);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $isBinary ? $encrypted : bin2hex($encrypted);
    }

    /**
     * @param string $code
     * @param bool $isBinary whether to decrypt as binary or not. Default is: false
     * @return string Decrypted data
     */
    private function decryptInternal($code, $isBinary = false)
    {
        $code = $isBinary ? $code : $this->hex2bin($code);
        $iv = $this->iv;
        $td = mcrypt_module_open('rijndael-128', ' ', 'cbc', $iv);
        mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $code);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $isBinary ? trim($decrypted) : utf8_encode(trim($decrypted));
    }

    private function hex2bin($hexdata)
    {
        $bindata = '';
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    public static function getInstance(){
        if(!extension_loaded('mcrypt')){
            throw new \Exception("A extensão mcrypt não foi instalada!");
        }
        if(is_null(self::$instance) || !self::$instance instanceof Crypt){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function changeKeys($iv, $key){
        self::getInstance()->iv  = strlen($iv)  ? $iv  : self::getInstance()->iv;
        self::getInstance()->key = strlen($key) ? $key : self::getInstance()->key;
        return self::getInstance();
    }

    /**
     * @param string $str
     * @param bool $isBinary whether to encrypt as binary or not. Default is: false
     * @return string Encrypted data
     */
    public static function encrypt($str, $isBinary = false){
        return self::getInstance()->encryptInternal($str, $isBinary);
    }

    /**
     * @param string $code
     * @param bool $isBinary whether to decrypt as binary or not. Default is: false
     * @return string Decrypted data
     */
    public static function decrypt($code, $isBinary = false){
        return self::getInstance()->decryptInternal($code, $isBinary);
    }
}