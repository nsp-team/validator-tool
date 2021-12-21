<?php

namespace NspTeam\Component\Validator\Output;

/**
 * Subject is an object for communicating the internal state of a Chain to an output object.
 * @package NspTeam\Component\Validator\Output
 */
class Subject
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Rule[]
     */
    protected $rules;

    /**
     * @param string $key
     * @param string $name
     */
    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * Adds a rule for this subject.
     *
     * @param Rule $rule
     */
    public function addRule(Rule $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * Returns the key for this subject.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Returns the name for this subject.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns an array of all rules in this subject.
     *
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}