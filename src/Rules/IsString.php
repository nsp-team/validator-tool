<?php

namespace NspTeam\Component\Validator\Rules;

class IsString extends AbstractRule
{

    /**
     * A constant that will be used when the value does not represent a string.
     */
    public const NOT_A_STRING = 'IsString::NOT_A_STRING';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_A_STRING => '{{ name }} 必须是字符串',
    ];


    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (is_string($value)) {
            return true;
        }

        return $this->error(self::NOT_A_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBreakChainOnError():bool
    {
        return true;
    }
}