<?php
/**
 * Created by PhpStorm.
 * User: jhordan
 * Date: 12/08/16
 * Time: 15:09
 */

require_once __DIR__. "./../includes/Validator.php";

$dados = \MocaBonita\includes\Validator::check([
    //"nome" => "11",
    "senha" => "asdasdassa2343242%@gmail.com#%#^d"
], [
    "nome"  => "numeric : 4 : 10",
    "senha" => "string | required | F_str_email"
], true, true);

//Verificar se os dados não foram aceitos para retornar a mensagem de erro
if(!$dados){
    $mensagemErro = "Dimensao: ";

    //Processar todas as mensagens de erro de uma unica vez
    foreach (\MocaBonita\includes\Validator::getMessages() as $message)
        $mensagemErro .= implode("<br>", $message) . "<br>";

    echo $mensagemErro;
}
echo $dados['senha'];
//var_dump();