<?php

declare(strict_types=1);

namespace NspTeam\Component\Validator;


use NspTeam\Component\Validator\Value\Container;

/**
 * 全局数据验证类。
 */
class Validator
{
    /**
     * The default context (if no context is currently active).
     */
    public const DEFAULT_CONTEXT = 'default';

    /**
     * Contains an array of context => Chain objects.
     *
     * @var array
     */
    protected $chains = [
        self::DEFAULT_CONTEXT => [],
    ];

    /**
     * Contains an array of context => MessageStack.
     *
     * @var list<string, MessageStack>
     */
    protected $messageStacks = [];

    /**
     * Contains the name of the current context.
     *
     * @var string
     */
    protected $context;

    /**
     * Construct the validator.
     */
    public function __construct()
    {
        $this->context = self::DEFAULT_CONTEXT;
        $this->messageStacks[$this->context] = new MessageStack();
    }

    public function validate(array $values, $context = self::DEFAULT_CONTEXT): Result
    {
        $isValid = true;
        $output = new Container();
        $input = new Container($values);
        $stack = $this->getMergedMessageStack($context);

        foreach ($this->chains[$context] as $chain) {
            /** @var Chain $chain */
//            $isValid = $chain->validate($stack, $input, $output);
//            if ($isValid === false) {
//                break;
//            }
            $isValid = $chain->validate($stack, $input, $output) && $isValid;
        }

        $result = new Result(
            $isValid,
            $stack->getFailures(),
            $output->getArrayCopy()
        );

        $stack->reset();

        return $result;
    }

    /**
     * Output the structure of the Validator by calling the $output callable with a representation of Validators'
     * internal structure.
     *
     * @param callable $output
     * @param string $context
     * @return mixed
     */
    public function output(callable $output, string $context = self::DEFAULT_CONTEXT)
    {
        $stack = $this->getMessageStack($context);

        $structure = new Output\Structure();
        if (array_key_exists($context, $this->chains)) {
            /* @var Chain $chain */
            foreach ($this->chains[$context] as $chain) {
                $chain->output($structure, $stack);
            }
        }

        return call_user_func($output, $structure);
    }

    /**
     * Overwrite the messages for specific keys.
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteMessages(array $messages): self
    {
        $this->getMessageStack($this->context)->overwriteMessages($messages);
        return $this;
    }

    /**
     * Overwrite the default messages with custom messages.
     *
     * @param array $messages
     * @return $this
     */
    public function overwriteDefaultMessages(array $messages): self
    {
        $this->getMessageStack($this->context)->overwriteDefaultMessages($messages);
        return $this;
    }


    /**
     * Creates a new required Validation Chain for the key $key.
     * @param string $key
     * @param string|null $name
     * @param bool $allowEmpty
     * @return Chain
     */
    public function required(string $key, ?string $name = null, bool $allowEmpty = false): Chain
    {
        return $this->getChain($key, $name, true, $allowEmpty);
    }

    /**
     * Creates a new optional Validation Chain for the key $key.
     * @param string $key
     * @param string|null $name
     * @param bool $allowEmpty
     * @return Chain
     */
    public function optional(string $key, string $name = null, bool $allowEmpty = true): Chain
    {
        return $this->getChain($key, $name, false, $allowEmpty);
    }

    /**
     * Retrieves a Chain object, or builds one if it doesn't exist yet.
     *
     * @param string $key
     * @param string|null $name
     * @param bool $required
     * @param bool $allowEmpty
     * @return Chain
     */
    protected function getChain(string $key, ?string $name, bool $required, bool $allowEmpty): Chain
    {
        if (isset($this->chains[$this->context][$key])) {
            /** @var Chain $chain */
            $chain = $this->chains[$this->context][$key];
            $chain->required($required);
            $chain->allowEmpty($allowEmpty);

            return $chain;
        }

        return $this->chains[$this->context][$key] = $this->buildChain($key, $name, $required, $allowEmpty);
    }

    /**
     * Build a new Chain object and return it.
     *
     * @param string $key
     * @param string $name
     * @param bool $required
     * @param bool $allowEmpty
     * @return Chain
     */
    protected function buildChain($key, $name, $required, $allowEmpty)
    {
        return new Chain($key, $name, $required, $allowEmpty);
    }

    /**
     * Returns the message stack.
     * if the context isn't the default context, it will merge the message of the default context first.
     *
     * @param string $context
     * @return MessageStack
     */
    protected function getMergedMessageStack(string $context): MessageStack
    {
        $stack = $this->getMessageStack($context);

        if ($context !== self::DEFAULT_CONTEXT) {
            $stack->merge($this->getMessageStack(self::DEFAULT_CONTEXT));
        }

        return $stack;
    }

    /**
     * Returns a message stack for the context $context.
     *
     * @param string $context
     * @return MessageStack
     */
    protected function getMessageStack(string $context): MessageStack
    {
        return $this->messageStacks[$context];
    }
}