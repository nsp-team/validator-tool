<?php


use NspTeam\Component\Validator\Validator;

require_once '../vendor/autoload.php';

// 参考；https://github.com/particle-php/Validator/blob/657c7543e5/src/Rule/Required.php

$values = [
    'id' => 1,
    'value' => 9000,
    'phone' => 13402383780,
    'link' => 'http://validator',
    'optional' => '',
    'forceOptional' => true,
    'json' => '{"ss:1}',
    'length' => 'ThisIsALongAndProperP4ssW0rdWithNoMaxLength',
    'date' => '2015-10-28',
    'amount' => '2500',
    'money' => '252002.2',
    'name' => 'John!',
    'nickname' => 'John hali',
    'passwordPresent' => false,
    'newsletter' => 'true',
    'password' => '',
    'email' => '123@qq.com',
    'lines' => array(
        array(
            'amount' => 500,
            'description' => 'First line',
        ),
        array(
            'amount' => 2000,
            'description' => 'Second line',
        ),
    ),
];

$validator = new Validator();


$validator->required('id')->integer();
$validator->required('amount', '金额')->integer(true);

# datetime
$validator->required('date')->datetime('Y-m-d H:i:s');
$validator->required('date')->datetime();

// 当 $values['passwordPresent'] === true 时， password 允许为 empty
$validator->required('password', '密码')->allowEmpty(function (array $values) {
    return $values['passwordPresent'] === true;
});

# alnum (a-z, A-Z, 0-9).
$validator->required('name')->alnum();

# alpha (a-z, A-Z).
$validator->required('lines.0.description')->alpha();

# between
$validator->required('lines.0.amount')->between(501, 1000);

# bool
$validator->required('newsletter')->bool();

# digits, 允许字符串数字
$validator->required('money')->digits();

# each, 应用于重复的嵌套数组
$validator->required('lines')->each(function (Validator $validator) {
    $validator->required('amount')->integer();
    $validator->required('description')->lengthBetween(0, 100);
});

# email
$validator->required('email')->email();

# equals
$validator->required('id')->equals(2);

# float
$validator->required('id')->float();

# greaterThan
$validator->required('value')->greaterThan(9001);

# lessThan
$validator->required('value')->lessThan(1001);

# inArray
$validator->required('value')->inArray([1000, 90001, 10000]);

# isArray
$validator->required('value')->isArray();

# json
$validator->required('json')->json();

# length 验证该值的长度是否正好为$length
$validator->required('json')->length(7);

# lengthBetween
$validator->required('length')->lengthBetween(80, 120);

# numeric (either a float, or an integer or string number)
$validator->required('value')->numeric();
$validator->required('money')->numeric();

# phone
$validator->required('phone')->phone();



# required (option：可选参数，后面跟required,作为条件判断，啥时候应该必传。
$validator->optional('optional')->required(true);
// 可选参 不许为空
//$validator->optional('optional', '可选参', true)->required(function (array $values){
//    return $values['forceOptional'] === true;
//});

# string
$validator->required('phone')->string();

# url
$validator->required('link')->url();

# regex
$validator->required('name')->regex('/^jhn/i');

// callback
$validator->required('link', '链接')->callback(function ($value, array $values) {
    return !(strpos($value, 'https') === false);
});

//$validator->required('optional');
$result = $validator->validate($values);
if (!$result->isValid()) {
//    var_dump($result->getMessages());
    var_dump($result->getFirstMessage($errorsMessage)); // bool(true)
    var_dump($errorsMessage);
}


