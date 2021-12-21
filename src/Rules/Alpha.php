<?php

namespace NspTeam\Component\Validator\Rules;

/**
 * 此规则检查值是否仅由字母字符组成
 */
class Alpha extends AbstractRule
{
    /**
     * A constant that will be used for the error message when the value contains non-alphabetic characters.
     */
    public const NOT_ALPHA = 'Alpha::NOT_ALPHA';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ALPHA => '{{ name }} 只能由字母字符组成'
    ];

    protected $allowSpaces;

    public function __construct($allowSpaces = false)
    {
        $this->allowSpaces = $allowSpaces;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if ($this->allowSpaces) {
            $values = explode(' ', $value);
            $isAlpha = true;

            foreach ($values as $v) {
                $isAlpha =  !ctype_alnum((string) $v) && $isAlpha;
            }
            if ($isAlpha === false) {
                return $this->error(self::NOT_ALPHA);
            }
            return true;
        }

        if (ctype_alpha((string) $value)) {
            return true;
        }
        return $this->error(self::NOT_ALPHA);
    }
}