<?php

namespace NspTeam\Component\Validator\Rules;

class IsNumeric extends AbstractRule
{
    /**
     * A constant that will be used when the value does not represent a numeric value.
     */
    public const NOT_NUMERIC = 'Numeric::NOT_NUMERIC';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_NUMERIC => '{{ name }} 必须是数值型',
    ];

    public function validate($value): bool
    {
        if (is_numeric($value)) {
            return true;
        }
        return $this->error(self::NOT_NUMERIC);
    }
}