<?php

namespace NspTeam\Component\Validator\Rules;

class GreaterThan extends AbstractRule
{
    /**
     * A constant for an error message if the value is not greater than the min.
     */
    public const NOT_GREATER_THAN = 'GreaterThan::NOT_GREATER_THAN';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_GREATER_THAN => '{{ name }} 必须要大于 {{ min }}',
    ];

    /**
     * The lower boundary.
     *
     * @var int
     */
    protected $min;

    /**
     * Construct the GreaterThan rule.
     *
     * @param int $min
     */
    public function __construct(int $min)
    {
        $this->min = $min;
    }
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if ($value <= $this->min) {
            $this->error(self::NOT_GREATER_THAN);
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
            'min' => $this->min,
        ]);
    }
}