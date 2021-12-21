<?php

namespace NspTeam\Component\Validator\Rules;

class IsInteger extends AbstractRule
{
    /**
     * A constant that will be used when the value does not represent an integer value.
     */
    public const NOT_AN_INTEGER = 'Integer::NOT_AN_INTEGER';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_AN_INTEGER => '{{ name }} 必须是int类型',
    ];

    /**
     * A bool denoting whether or not strict checking should be done.
     *
     * @var bool
     */
    private $strict;


    public function __construct($strict = false)
    {
        $this->strict = $strict;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if ($this->strict && is_int($value)) {
            return true;
        }

        if (!$this->strict && false !== filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }

        return $this->error(self::NOT_AN_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBreakChainOnError():bool
    {
        return true;
    }
}