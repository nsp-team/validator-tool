<?php

namespace NspTeam\Component\Validator\Rules;

class IsJson extends AbstractRule
{
    /**
     * A constant that will be used when the value is not a valid JSON string.
     */
    public const INVALID_FORMAT = 'Json::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_FORMAT => '{{ name }} 必须是有效的json字符串',
    ];

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (!is_string($value)) {
            return $this->error(self::INVALID_FORMAT);
        }

        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->error(self::INVALID_FORMAT);
        }

        return true;
    }
}