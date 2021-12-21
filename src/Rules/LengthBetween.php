<?php

namespace NspTeam\Component\Validator\Rules;

class LengthBetween extends Between
{
    /**
     * A constant that is used when the value is too long.
     */
    public const TOO_LONG = 'LengthBetween::TOO_LONG';

    /**
     * A constant that is used when the value is too short.
     */
    public const TOO_SHORT = 'LengthBetween::TOO_SHORT';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::TOO_LONG => '{{ name }} 字符串长度太长，必须在 {{ min }}-{{ max }} 个字符长度之间',
        self::TOO_SHORT => '{{ name }} 字符串长度太短，必须在 {{ min }}-{{ max }} 个字符长度之间'
    ];

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $length = strlen($value);

        return !$this->tooSmall($length, self::TOO_SHORT) && !$this->tooLarge($length, self::TOO_LONG);
    }

    protected function tooLarge($value, $error): bool
    {
        if ($this->max !== null) {
            return parent::tooLarge($value, $error);
        }
        return false;
    }

    protected function getMessageParameters():array
    {
        return array_merge(parent::getMessageParameters(), [
            'min' => $this->min,
            'max' => $this->max
        ]);
    }
}