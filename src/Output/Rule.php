<?php

namespace NspTeam\Component\Validator\Output;

use NspTeam\Component\Validator\MessageStack;

/**
 * The Rule class is a representation of an actual rule for displaying purposes.
 *
 * @package NspTeam\Component\Validator\Output
 */
class Rule
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param string $name
     * @param array $messages
     * @param array $parameters
     */
    public function __construct(string $name, array $messages, array $parameters)
    {
        $this->name = $name;
        $this->messages = $messages;
        $this->parameters = $parameters;
    }

    /**
     * Returns the name (short class name) for this rule.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns all messages for this rule.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Returns all parameters for this rule.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}