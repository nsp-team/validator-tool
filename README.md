**nsp-team/validator-tool** 是一个非常小的验证库，尽可能的为我们创建的最简单和最有用的 API。

- 说明

  项目为 https://github.com/particle-php/Validator 的汉化版本，优化了部分内容，增加了部分校验规则，如：Ip等。
  另外PHP版本升级到7.3，使用强类型定义变量和返回值类型，用法保持不变。

> 提供了全部汉化的语义描述，当然您也可以覆盖当前已设置的语义描述，采用自定义的错误描述信息.

## Install

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

## 命名规范
遵循PSR-2命名规范和PSR-4自动加载规范。

## features

* 零依赖
* 验证对象是数组参数
* 可以获取错误信息数组
* 提供了覆盖规则上的默认错误消息方式, 或特定值上的错误消息
* 获取数组的验证值
* 大量的默认验证规则
* 能够扩展验证器以添加您自己的自定义规则


## 文档
后期我会提供一份教程文档使用方式，开发者可以先自定阅读源码。

## Thanks

[particle-php/Validator](https://github.com/particle-php/Validator)

[yiisoft/validator](https://github.com/yiisoft/validator)