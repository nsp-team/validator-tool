<?php

namespace NspTeam\Component\Validator\Rules;

class Email extends AbstractRule
{
    /**
     * A constant that will be used when the value is not a valid e-mail address.
     */
    const INVALID_FORMAT = 'Email::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_FORMAT => '{{ name }} 必须是有效的邮箱地址',
    ];
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }
        return $this->error(self::INVALID_FORMAT);
    }
}