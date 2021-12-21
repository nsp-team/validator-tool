<?php

namespace NspTeam\Component\Validator\Rules;

class IsPhone extends AbstractRule
{
    /**
     * Constants that will be used when an invalid phone number is passed.
     */
    public const INVALID_VALUE = 'Phone::INVALID_VALUE';
    public const INVALID_FORMAT = 'Phone::INVALID_FORMAT';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_VALUE => '{{ name }} 必须是有效的电话号码',
        self::INVALID_FORMAT => '{{ name }} 必须是有效的电话号码格式',
    ];


    public function validate($value): bool
    {
        if (!is_numeric($value)) {
            return $this->error(self::INVALID_VALUE);
        }

        $result = preg_match('/^1[345789]\d{9}$/', $value);

        if ($result === 0) {
            return $this->error(self::INVALID_FORMAT);
        }
        return true;
    }
}