<?php

namespace NspTeam\Component\Validator\Rules;

/**
 * 此规则用于验证字符串是否仅由数字组成。
 */
class Digits extends AbstractRule
{
    /**
     * A constant that will be used when the value contains things other than digits.
     */
    public const NOT_DIGITS = 'Digits::NOT_DIGITS';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_DIGITS => '{{ name }} 字符串是只能由数字组成',
    ];

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (ctype_digit((string) $value)) {
            return true;
        }
        return $this->error(self::NOT_DIGITS);
    }
}