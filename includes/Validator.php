<?php
namespace MocaBonita\includes;

use \Exception;

class Validator
{

    private static $rules    = [];
    private static $messages = [];
    private static $messages_return = [
        'string_not_string'   => "O atributo '%attr' não é um string!",
        'string_min_length'   => "O atributo '%attr' deve ter no minimo '%min' caracteres!",
        'string_max_length'   => "O atributo '%attr' deve ter no maximo '%max' caracteres!",
        'numeric_not_numeric' => "O atributo '%attr' não é um número!",
        'numeric_min_value'   => "O atributo '%attr' deve ser maior ou igual a '%min'!",
        'numeric_max_value'   => "O atributo '%attr' deve ser menor ou igual a '%max'!",
        'integer_not_integer' => "O atributo '%attr' não é um inteiro!",
        'double_not_double'   => "O atributo '%attr' não é um double!",
        'array_not_array'     => "O atributo '%attr' não é um array!",
        'array_min_element'   => "O atributo '%attr' deve ter no minimo '%min' elementos!",
        'array_max_element'   => "O atributo '%attr' deve ter no máximo '%max' elementos!",
        'object_not_object'   => "O atributo '%attr' não é um objeto!",
        'object_instance_of'  => "O atributo '%attr' não é uma instância de '%class'!",
        'required'            => "O atributo '%attr' é obrigatório!",
        'required_not_empty'  => "O atributo '%attr' não pode está vazia!",
        'attr_not_found'      => "O atributo '%attr' não foi encontrado!",
        'rule_not_found'      => "A regra '%rule' não foi definida!"
    ];

    static public function getMessages(){
        return self::$messages;
    }

    static private function setMessages($messages)
    {
        self::$messages = $messages;
    }

    static public function changeMessage($key, $message){
        self::$messages_return[$key] = $message;
    }

    static public function check(array $data, array $rules, $dOData = false, $ignoreNotFound = false)
    {
        $attrs = array_keys($rules);

        self::setMessages([]);

        self::loadRules();

        foreach ($attrs as &$attr) {
            if (
                (!isset($data[$attr]) && !$ignoreNotFound) ||
                ((isset($data[$attr]) && is_null($data[$attr]) && !$ignoreNotFound))
            ){
                if(!isset(self::$messages[$attr]))
                    self::$messages[$attr] = [];

                self::$messages[$attr][] = str_replace(
                    '%attr',
                    $attr,
                    self::$messages_return['attr_not_found']
                );

            } elseif(!isset($data[$attr]) && $ignoreNotFound) {
                $data[$attr] = !isset($data[$attr]) ? null : $data[$attr];
                $rules_attr  = explode("|", $rules[$attr]);
                foreach ($rules_attr as $rule_attr)
                    self::processRule($rule_attr, $data[$attr], $attr);
            } else {
                $rules_attr = explode("|", $rules[$attr]);
                foreach ($rules_attr as $rule_attr)
                    self::processRule($rule_attr, $data[$attr], $attr);
            }

        }

        if($dOData){
            foreach ($data as $key => &$itemPost)
                if (!in_array($key, $attrs))
                    unset($data[$key]);
        }

        return empty(self::$messages) ? $data : false;
    }

    static private function loadRules(){

        self::$rules['string'] = function($data, $attr, array $params){
            $isString = is_string($data);
            $isNull = is_null($data);

            if(!$isNull){

                if(!$isString)
                    throw new Exception(str_replace(
                        ['%attr',],
                        [$attr,],
                        self::$messages_return['string_not_string']
                    ));

                if($isString && isset($params[1]) && $params[1] = (int) $params[1]){
                    $strLen = strlen($data);

                    if($strLen < $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%min',],
                            [$attr, $params[1],],
                            self::$messages_return['string_min_length']
                        ));

                    elseif(isset($params[2]) && $strLen > (int) $params[2])
                        throw new Exception(str_replace(
                            ['%attr', '%max'],
                            [$attr, $params[2]],
                            self::$messages_return['string_max_length']
                        ));

                }
            }
            
            return $data;

        };

        self::$rules['numeric'] = function($data, $attr, array $params){
            $isNumeric = is_numeric($data);
            $isNull = is_null($data);

            if(!$isNull) {
                if (!$isNumeric)
                    throw new Exception(str_replace(
                        ['%attr'],
                        [$attr],
                        self::$messages_return['numeric_not_numeric']
                    ));

                if ($isNumeric && isset($params[1]) && is_numeric($params[1])) {

                    if ($data < $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%min'],
                            [$attr, $params[1]],
                            self::$messages_return['numeric_min_value']
                        ));

                    elseif (isset($params[2]) && is_numeric($params[2]) && $data > $params[2])
                        throw new Exception(str_replace(
                            ['%attr', '%max'],
                            [$attr, $params[2]],
                            self::$messages_return['numeric_max_value']
                        ));
                }
            }

            return $data;

        };

        self::$rules['double'] = function($data, $attr, array $params){
            $isDouble = is_float($data);
            $isNull = is_null($data);

            if(!$isNull) {

                if (!$isDouble)
                    throw new Exception(str_replace(
                        ['%attr'],
                        [$attr,],
                        self::$messages_return['double_not_double']
                    ));

                if ($isDouble && isset($params[1]) && is_numeric($params[1])) {

                    if ($data < $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%min'],
                            [$attr, $params[1],],
                            self::$messages_return['numeric_min_value']
                        ));

                    elseif (isset($params[2]) && is_numeric($params[2]) && $data > $params[2])
                        throw new Exception(str_replace(
                            ['%attr', '%max',],
                            [$attr, $params[2],],
                            self::$messages_return['numeric_max_value']
                        ));
                }
            }

            return $data;

        };

        self::$rules['integer'] = function($data, $attr, array $params){
            $isInteger = is_int($data);
            $isNull = is_null($data);

            if(!$isNull) {

                if (!$isInteger)
                    throw new Exception(str_replace(
                        ['%attr',],
                        [$attr,],
                        self::$messages_return['integer_not_integer']
                    ));

                if ($isInteger && isset($params[1]) && is_numeric($params[1])) {

                    if ($data < $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%min'],
                            [$attr, $params[1]],
                            self::$messages_return['numeric_min_value']
                        ));

                    elseif (isset($params[2]) && is_numeric($params[2]) && $data > $params[2])
                        throw new Exception(str_replace(
                            ['%attr', '%max'],
                            [$attr,$params[2]],
                            self::$messages_return['numeric_max_value']
                        ));
                }
            }

            return $data;

        };

        self::$rules['array'] = function($data, $attr, array $params){
            $isArray = is_array($data);
            $isNull = is_null($data);

            if(!$isNull) {

                if (!$isArray)
                    throw new Exception(str_replace(
                        ['%attr',],
                        [$attr,],
                        self::$messages_return['array_not_array']
                    ));

                if ($isArray && isset($params[1]) && is_numeric($params[1])) {
                    $count = count($data);

                    if ($count < $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%min',],
                            [$attr, $params[1],],
                            self::$messages_return['array_min_element']
                        ));

                    elseif (isset($params[2]) && is_numeric($params[2]) && $count > $params[2])
                        throw new Exception(str_replace(
                            ['%attr','%max'],
                            [$attr, $params[2]],
                            self::$messages_return['array_max_element']
                        ));
                }
            }

            return $data;

        };

        self::$rules['object'] = function($data, $attr, array $params){
            $isObject = is_object($data);
            $isNull = is_null($data);

            if(!$isNull) {

                if (!$isObject)
                    throw new Exception(str_replace(
                        ['%attr'],
                        [$attr,],
                        self::$messages_return['object_not_object']
                    ));

                if ($isObject && isset($params[1]) && is_string($params[1])) {
                    if (!$data instanceof $params[1])
                        throw new Exception(str_replace(
                            ['%attr', '%class'],
                            [$attr, $params[1]],
                            self::$messages_return['object_instance_of']
                        ));
                }
            }

            return $data;

        };

        self::$rules['required'] = function($data, $attr, array $params){
            $isNull = is_null($data);

            if($isNull)
                throw new Exception(str_replace(
                    ['%attr'], [$attr], self::$messages_return['required']
                ));

            if(
                (is_string($data) && strlen($data) == 0) ||
                (is_array($data) && count($data) == 0)
            )
                throw new Exception(str_replace(
                    ['%attr'], [$attr], self::$messages_return['required_not_empty']
                ));

            return $data;

        };

        ///Filters

        self::$rules['F_str_striptags'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = strip_tags($data);
            }

            return $data;

        };

        self::$rules['F_str_trim'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = trim($data);
            }

            return $data;

        };

        self::$rules['F_str_lower'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = strtolower($data);
            }

            return $data;

        };

        self::$rules['F_str_upper'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = strtoupper($data);
            }

            return $data;

        };

        self::$rules['F_str_htmlescape'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = htmlspecialchars($data);
            }

            return $data;

        };

        self::$rules['F_str_alphanum'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = preg_replace("/[^a-zA-Z0-9]+/", "", $data);
            }

            return $data;

        };

        self::$rules['F_str_float'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }

            return $data;

        };

        self::$rules['F_str_int'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            }

            return $data;

        };

        self::$rules['F_str_email'] = function($data){
            $isString = is_string($data);

            if($isString){
                $data = filter_var($data, FILTER_SANITIZE_EMAIL, FILTER_FLAG_ALLOW_FRACTION);
            }

            return $data;

        };

        return self::$rules;
    }

    static public function addRules($rule, $callback){
        if(is_null(self::$rules))
            self::$rules = [];

        if(!isset(self::$rules[$rule]))
            self::$rules[$rule] = $callback;
    }

    static private function processRule($rule, &$data, $attr){
        $rules_param = explode(":", $rule);

        foreach($rules_param as &$params)
            $params = trim($params);

        try{
            if(!isset(self::$rules[$rules_param[0]]))
                throw new Exception(str_replace(
                    '%rule',
                    $rules_param[0],
                    self::$messages_return['rule_not_found']
                ));

            $function = self::$rules[$rules_param[0]];
            $dataReturn = $function($data, $attr, $rules_param);
            $data = !is_null($dataReturn) ? $dataReturn : $data;
        } catch(Exception $e){
            if(!isset(self::$messages[$attr]))
                self::$messages[$attr] = [];

            self::$messages[$attr][] = $e->getMessage();
        }
    }
}