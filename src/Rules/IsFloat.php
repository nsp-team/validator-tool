<?php

namespace NspTeam\Component\Validator\Rules;

class IsFloat extends AbstractRule
{
    /**
     * A constant that will be used when the value does not represent a float.
     */
    public const NOT_A_FLOAT = 'IsFloat::NOT_A_FLOAT';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_A_FLOAT => '{{ name }} 必须是浮点数',
    ];

    /**
     * 验证 $value 是否表示浮点数。
     *
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (is_float($value)) {
            return true;
        }

        return $this->error(self::NOT_A_FLOAT);
    }

    /**
     * @inheritdoc
     */
    public function shouldBreakChainOnError():bool
    {
        return true;
    }
}