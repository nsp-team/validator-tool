<?php

namespace NspTeam\Component\Validator\Rules;

class Between extends AbstractRule
{
    /**
     * A constant for an error message if the value is exceeding the max value.
     */
    public const TOO_BIG = 'Between::TOO_BIG';

    /**
     * A constant for an error message if the value is below the min value.
     */
    public const TOO_SMALL = 'Between::TOO_SMALL';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::TOO_BIG => '{{ name }} 必须小于等于 {{ max }}',
        self::TOO_SMALL => '{{ name }} 必须大于等于 {{ min }}',
    ];

    /**
     * The lower boundary.
     *
     * @var int
     */
    protected $min;

    /**
     * The upper boundary.
     *
     * @var int
     */
    protected $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return !$this->tooSmall($value, self::TOO_SMALL) && !$this->tooLarge($value, self::TOO_BIG);
    }

    protected function tooSmall($value, $error): bool
    {
        if ($value < $this->min) {
            $this->error($error);
            return true;
        }
        return false;
    }

    protected function tooLarge($value, $error): bool
    {
        if ($value > $this->max) {
            $this->error($error);
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function getMessageParameters():array
    {
        return array_merge(parent::getMessageParameters(), [
            'min' => $this->min,
            'max' => $this->max
        ]);
    }
}