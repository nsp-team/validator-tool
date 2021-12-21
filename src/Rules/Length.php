<?php

namespace NspTeam\Component\Validator\Rules;

class Length extends AbstractRule
{
    /**
     * A constant that will be used for the error message when the value is too short.
     */
    public const TOO_SHORT = 'Length::TOO_SHORT';

    /**
     * A constant that will be used for the error message when the value is too long.
     */
    public const TOO_LONG = 'Length::TOO_LONG';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::TOO_SHORT => '{{ name }} 太短，长度必须为 {length}} 个字符长度',
        self::TOO_LONG => '{{ name }} 太长，长度必须为 {length}} 个字符长度',
    ];

    /**
     * The length the value should have.
     *
     * @var int
     */
    protected $length;

    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $actualLength = strlen($value);

        if ($actualLength > $this->length) {
            return $this->error(self::TOO_LONG);
        }
        if ($actualLength < $this->length) {
            return $this->error(self::TOO_SHORT);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function getMessageParameters(): array
    {
        return array_merge(parent::getMessageParameters(), [
            'length' => $this->length
        ]);
    }
}