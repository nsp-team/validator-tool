<?php

namespace NspTeam\Component\Validator\Rules;

class IsArray extends AbstractRule
{
    /**
     * A constant that will be used when the value does not represent an integer value.
     */
    public const NOT_AN_ARRAY = 'IsArray::NOT_AN_ARRAY';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_AN_ARRAY => '{{ name }} 必须是数组',
    ];

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        return $this->error(self::NOT_AN_ARRAY);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBreakChainOnError():bool
    {
        return true;
    }
}