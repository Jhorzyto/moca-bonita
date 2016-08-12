<?php
/**
 * Created by PhpStorm.
 * User: jhordan
 * Date: 12/08/16
 * Time: 14:15
 */

require_once __DIR__ . './../includes/Crypt.php';

try {
    \MocaBonita\includes\Crypt::changeKeys("9876543210fedcba", "");
    echo \MocaBonita\includes\Crypt::encrypt("Jhordan");
    echo "<br>";
    echo \MocaBonita\includes\Crypt::decrypt("56985eeed6125f0f6910c71d3c6799dc");
} catch (Exception $e){
    echo $e->getMessage();
}
