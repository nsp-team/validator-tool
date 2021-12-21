<?php

namespace NspTeam\Component\Validator\Rules;

class IsBoolean extends AbstractRule
{
    /**
     * A constant that will be used when the value is not in the array without strict checking.
     */
    public const NOT_BOOL = 'BOOL::NOT_BOOL';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_BOOL => '{{ name }} 必须为true or false',
    ];


    public function validate($value): bool
    {
        return is_bool($value) || $this->error(self::NOT_BOOL);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBreakChainOnError():bool
    {
        return true;
    }
}