<?php

declare(strict_types=1);

namespace NspTeam\Component\Validator;

class Result
{
    /**
     * @var bool
     */
    protected $isValid;

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var Failure[]
     */
    protected $failures;


    /**
     * Construct the validation result.
     *
     * @param bool $isValid
     * @param array $failures
     * @param array $values
     */
    public function __construct(bool $isValid, array $failures, array $values)
    {
        $this->isValid = $isValid;
        $this->failures = $failures;
        $this->values = $values;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isNotValid(): bool
    {
        return !$this->isValid;
    }

    /**
     * Returns the array of messages that were collected during validation.
     *
     * @return array
     */
    public function getMessages(): array
    {
        if ($this->messages === null) {
            $this->messages = [];
            foreach ($this->failures as $failure) {
                $this->messages[$failure->getKey()][$failure->getReason()] = $failure->format();
            }
        }

        return $this->messages;
    }

    public function getFirstMessage(&$errorsMessage =[])
    {
        $messages = [];
        foreach ($this->getMessages() as $message) {
            $errorsMessage[]= $messages[] = array_values($message)[0];
        }

        return current($messages);
    }

    /**
     * @return Failure[]
     */
    public function getFailures(): array
    {
        return $this->failures;
    }

    /**
     * Returns all validated values
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
}