<?php

namespace NspTeam\Component\Validator\Rules;

use NspTeam\Component\Validator\Failure;
use NspTeam\Component\Validator\MessageStack;
use NspTeam\Component\Validator\Output\Rule;
use NspTeam\Component\Validator\Output\Subject;
use NspTeam\Component\Validator\Value\Container;

/**
 * AbstractRule是所有规则的抽象父类，并定义了它们的共同行为
 * @package NspTeam\Component\Validator\Rules
 */
abstract class AbstractRule
{
    /**
     * 包含了验证的所有值的数组
     * @var array
     */
    protected $values;

    /**
     * 包含了在验证错误时返回的消息数组。
     *
     * @var array
     */
    protected $messageTemplates = [];

    /**
     * Contains a reference to the MessageStack to append errors to.
     *
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * The key we have to validate the value of.
     *
     * @var string
     */
    protected $key;

    /**
     * The name may be used in validation error messages.
     *
     * @var string|null
     */
    protected $name;

    /**
     * This method should validate, possibly log errors, and return the result as a boolean.
     *
     * @param mixed $value
     * @return bool
     */
    abstract public function validate($value): bool;

    /**
     * This indicates whether or not the rule can and should break the chain it's in.
     *
     * @return bool
     */
    public function shouldBreakChain(): bool
    {
        return false;
    }

    /**
     * This indicates whether or not the rule should break the chain it's in on validation failure.
     *
     * @return bool
     */
    public function shouldBreakChainOnError(): bool
    {
        return false;
    }

    /**
     * Registers the message stack to append errors to.
     *
     * @param MessageStack $messageStack
     * @return $this
     */
    public function setMessageStack(MessageStack $messageStack):self
    {
        $this->messageStack = $messageStack;
        return $this;
    }

    /**
     * Sets the default parameters for each validation rule (key and name).
     *
     * @param string $key
     * @param string|null $name
     * @return $this
     */
    public function setParameters(string $key, ?string $name):self
    {
        $this->key = $key;
        $this->name = $name;
        return $this;
    }

    /**
     * Determines whether or not the value of $key is valid in the array $values and returns the result as a bool.
     *
     * @param string $key
     * @param Container $input
     * @return bool
     */
    public function isValid(string $key, Container $input): bool
    {
//        $this->values = $input->getArrayCopy();

        return $this->validate($input->get($key));
    }

    /**
     * Attach a representation of this rule to the Output\Subject $subject.
     *
     * @param Subject $subject
     * @param MessageStack $messageStack
     */
    public function output(Subject $subject, MessageStack $messageStack): void
    {
        $this->setParameters($subject->getKey(), $subject->getName());

        $outputRule = new Rule(
            $this->getShortName(),
            $this->getMessageTemplates($messageStack),
            $this->getMessageParameters()
        );

        $subject->addRule($outputRule);
    }

    /**
     * Appends the error for reason $reason to the MessageStack.
     *
     * @param string $reason
     * @return bool
     */
    protected function error(string $reason): bool
    {
        $this->messageStack->append(
            new Failure(
                $this->key,
                $reason,
                $this->getMessage($reason),
                $this->getMessageParameters()
            )
        );

        return false;
    }


    /**
     * 返回this类的name，without the namespace
     *
     * @return string
     */
    protected function getShortName(): string
    {
        $className = get_class($this);
        return substr($className, strrpos($className, '\\') + 1);
    }

    /**
     * Returns an error message for the reason $reason, or an empty string if it doesn't exist.
     *
     * @param mixed $reason
     * @return string
     */
    protected function getMessage(string $reason): string
    {
        $messageTemplate = '';
        if (array_key_exists($reason, $this->messageTemplates)) {
            $messageTemplate = $this->messageTemplates[$reason];
        }

        return $messageTemplate;
    }

    /**
     *  returned an array of Message Templates.
     *
     * @param MessageStack $messageStack
     * @return array
     */
    protected function getMessageTemplates(MessageStack $messageStack): array
    {
        $messages = $this->messageTemplates;
        foreach ($messages as $reason => $message) {
            $overwrite = $messageStack->getOverwrite($reason, $this->key);

            if (is_string($overwrite)) {
                $messages[$reason] = $overwrite;
            }
        }

        return $messages;
    }

    /**
     * Return an array of all parameters that might be replaced in the validation error messages.
     *
     * @return array
     */
    protected function getMessageParameters(): array
    {
        $name = $this->name ?? str_replace('_', ' ', $this->key);

        return [
            'key' => $this->key,
            'name' => $name,
        ];
    }
}