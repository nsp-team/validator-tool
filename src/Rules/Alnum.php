<?php

namespace NspTeam\Component\Validator\Rules;

/**
 * 此规则检查值是否仅由字母字符组成
 */
class Alnum extends AbstractRule
{
    /**
     * A constant that will be used for the error message when the value contains non-alphabetic characters.
     */
    public const  NOT_ALNUM = 'Alnum::NOT_ALNUM';


    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_ALNUM => '{{ name }} 只能由数字和字母字符组成'
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
            $isAlnum = true;

            foreach ($values as $v) {
                $isAlnum =  !ctype_alnum((string) $v) && $isAlnum;
            }
            if ($isAlnum === false) {
                return $this->error(self::NOT_ALNUM);
            }
            return true;
        }

        if (ctype_alnum((string) $value)) {
            return true;
        }
        return $this->error(self::NOT_ALNUM);


    }
}