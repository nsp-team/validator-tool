<?php


use NspTeam\Component\Validator\Validator;

require_once '../vendor/autoload.php';

// 参考；https://github.com/particle-php/Validator/blob/657c7543e5/src/Rule/Required.php

//$v = new Validator;
//$v->required('link')->url();
//$v->validate(['link' => 'http://validator.particle-php.com'])->isValid(); // true
//var_dump($v->validate(['link' => 'http://validator.particle-php'])->isValid()); // false



$validator = new Validator();
$validator->required('username', '账号')->string();
$validator->required('password', '密码')->string()->length(32);
$result = $validator->validate(array('username' => '11', 'password' =>'22'));

if ($result->isNotValid()) {
    var_dump($result->getFirstMessage($errors));
}