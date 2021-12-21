
*nsp-team/validator-tool* 是一个非常小的验证库，尽可能的为我们创建的最简单和最有用的 API。

> 本包提供了全部中文化的语义描述，当然您也可以覆盖当前已设置的语义描述，采用自定义的错误描述信息.

## Install
为了方便使用 *nsp-team/validator-too* , 请使用 [composer](https://getcomposer.org) 安装它:

```bash
composer require nsp-team/validator-tool
```

## Simple usage

```php

$v = new Validator;

$v->required('user.first_name')->lengthBetween(2, 50)->alpha();
$v->required('user.last_name')->lengthBetween(2, 50)->alpha();
$v->required('newsletter')->bool();

$result = $v->validate([
    'user' => [
        'first_name' => 'John',
        'last_name' => 'D',
    ],
    'newsletter' => true,
]);

$result->isValid(); // bool(false).
$result->getMessages();
/**
 * array(1) {
 *     ["user.last_name"]=> array(1) {
 *         ["Length::TOO_SHORT"]=> string(53) "last_name  字符串长度太短，必须在 80-120 个字符长度之间."
 *     }
 * }
 */
```

## features

* 验证对象是数组参数
* 可以获取错误信息数组
* 提供了覆盖规则上的默认错误消息方式, 或特定值上的错误消息
* 获取数组的验证值
* 大量的默认验证规则
* 能够扩展验证器以添加您自己的自定义规则
* 零依赖


后期我会提供一份教程文档使用方式，开发者可以先自定阅读源码。

## Thanks
[particle-php/Validator](https://github.com/particle-php/Validator)

[yiisoft/validator](https://github.com/yiisoft/validator)