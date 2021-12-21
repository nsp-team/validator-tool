<?php

namespace NspTeam\Component\Validator\Rules;

class Regex extends AbstractRule
{
    /**
     * A constant that will be used when the value doesn't match the regex.
     */
    public const NO_MATCH = 'Regex::NO_MATCH';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NO_MATCH => '{{ name }} 不正确'
    ];

    /**
     * The regex that should be matched.
     *
     * @var string
     */
    protected $regex;

    /**
     * Construct the Regex rule.
     *
     * @param string $regex
     */
    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::NO_MATCH);
    }

    /**
     * A method to match against a regex. If it doesn't match, it will log the message $reason.
     *
     * @param string $regex
     * @param mixed $value
     * @param string $reason
     * @return bool
     */
    protected function match(string $regex, $value, string $reason): bool
    {
        $result = preg_match($regex, $value);

        if ($result === 0) {
            return $this->error($reason);
        }
        return true;
    }
}