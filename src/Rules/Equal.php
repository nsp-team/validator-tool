<?php

namespace NspTeam\Component\Validator\Rules;

class Equal extends AbstractRule
{
    /**
     * A constant that will be used when the value is not equal to the expected value.
     */
    public const NOT_EQUAL = 'Equal::NOT_EQUAL';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_EQUAL => '{{ name }} 必须等于 "{{ value }}"'
    ];

    /**
     * @var mixed
     */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if ($this->value === $value) {
            return true;
        }
        return $this->error(self::NOT_EQUAL);
    }

    protected function getMessageParameters(): array
    {
        return array_merge(parent::getMessageParameters(), [
            'value' => $this->value
        ]);
    }
}