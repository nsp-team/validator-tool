<?php

namespace NspTeam\Component\Validator\Rules;

class LessThan extends AbstractRule
{
    /**
     * A constant for an error message if the value is not less than the max.
     */
    public const NOT_LESS_THAN = 'LessThan::NOT_LESS_THAN';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_LESS_THAN => '{{ name }} 必须小于 {{ max }}',
    ];

    /**
     * The upper boundary.
     *
     * @var int
     */
    protected $max;

    /**
     * Construct the LessThan rule.
     *
     * @param int $max
     */
    public function __construct(int $max)
    {
        $this->max = $max;
    }

    /**
     * @inheritDoc
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        if ($value >= $this->max) {
            $this->error(self::NOT_LESS_THAN);
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     * @return array
     */
    protected function getMessageParameters():array
    {
        return array_merge(parent::getMessageParameters(), [
            'max' => $this->max,
        ]);
    }
}