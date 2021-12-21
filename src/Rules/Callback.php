<?php

namespace NspTeam\Component\Validator\Rules;

use NspTeam\Component\Validator\Value\Container;

class Callback extends AbstractRule
{
    use CallbackTrait;
    /**
     * A constant that will be used to indicate that the callback returned false.
     */
    public const INVALID_VALUE = 'Callback::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_VALUE => '{{ name }} is invalid',
    ];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var Container
     */
    protected $input;

    /**
     * Construct the Callback validator.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Validates the value according to this rule, and returns the result as a bool.
     *
     * @param string $key
     * @param Container $input
     * @return bool
     */
    public function isValid(string $key, Container $input):bool
    {
        $this->values = $input->getArrayCopy();

        return parent::isValid($key, $input);
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        try {
            $result = call_user_func($this->callback, $value, $this->values);
            if ($result === true) {
                return true;
            }
            return $this->error(self::INVALID_VALUE);
        } catch (\Exception $exception) {
            $reason = $exception->getCode();
            $this->messageTemplates[$reason] = $exception->getMessage();

            return $this->error($reason);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageParameters():array
    {
        return array_merge(parent::getMessageParameters(), [
            'callback' => $this->getCallbackAsString($this->callback),
        ]);
    }
}