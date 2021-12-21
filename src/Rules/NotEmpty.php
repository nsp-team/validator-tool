<?php

declare(strict_types=1);

namespace NspTeam\Component\Validator\Rules;


use NspTeam\Component\Validator\Value\Container;

/**
 * This class is responsible for checking if a certain key has a value.
 *
 * @package Particle\Validator\Rule
 */
class NotEmpty extends AbstractRule
{
    use CallbackTrait;

    /**
     * The error code for when a value is empty while this is not allowed.
     */
    public const EMPTY_VALUE = 'NotEmpty::EMPTY_VALUE';

    /**
     * The templates for the possible messages this validator can return.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::EMPTY_VALUE => '{{ name }} 不能为空',
    ];

    /**
     * Denotes whether or not the chain should be stopped after this rule.
     *
     * @var bool
     */
    protected $shouldBreak = false;

    /**
     * Indicates if the value can be empty.
     *
     * @var bool
     */
    protected $allowEmpty;

    /**
     * Optionally contains a callable to overwrite the allow empty requirement on time of validation.
     *
     * @var callable
     */
    protected $allowEmptyCallback;

    /**
     * Contains the input container.
     *
     * @var Container
     */
    protected $input;

    /**
     * Construct the NotEmpty validator.
     *
     * @param bool $allowEmpty
     */
    public function __construct(bool $allowEmpty)
    {
        $this->allowEmpty = $allowEmpty;
    }

    /**
     * @inheritDoc
     */
    public function shouldBreakChain(): bool
    {
        return $this->shouldBreak;
    }

    /**
     * Ensures a certain key has a value.
     * @inheritDoc
     */
    public function validate($value): bool
    {
        $this->shouldBreak = false;
        if ($this->isEmpty($value)) {
            $this->shouldBreak = true;
            if ($this->allowEmpty($this->input) === false) {
                return $this->error(self::EMPTY_VALUE);
            }
            return true;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isValid(string $key, Container $input): bool
    {
        $this->input = $input;
        return $this->validate($input->get($key));
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageParameters(): array
    {
        return array_merge(parent::getMessageParameters(), [
            'allowEmpty' => $this->allowEmpty,
            'callback' => $this->getCallbackAsString($this->allowEmptyCallback)
        ]);
    }

    /**
     * if value is null or '' or empty array， return true
     *
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value): bool
    {
        if ($value === '') {
            return true;
        }

        if ($value === null) {
            return true;
        }

        if (is_array($value) && count($value) === 0) {
            return true;
        }

        return false;
    }

    /**
     * 确定值是否可以为空
     *
     * @param Container $input
     * @return bool
     */
    protected function allowEmpty(Container $input): bool
    {
        if (isset($this->allowEmptyCallback)) {
            $this->allowEmpty = call_user_func($this->allowEmptyCallback, $input->getArrayCopy());
        }
        return $this->allowEmpty;
    }

    /**
     * 设置一个可调用或布尔值，以在验证时潜在地更改allow empty需求.
     * 这对于条件验证可能非常有用.
     *
     * @param callable|bool $allowEmpty
     * @return $this
     */
    public function setAllowEmpty($allowEmpty): NotEmpty
    {
        if (is_callable($allowEmpty)) {
            return $this->setAllowEmptyCallback($allowEmpty);
        }
        return $this->overwriteAllowEmpty($allowEmpty);
    }

    /**
     * Overwrite the allow empty requirement after instantiation of this rule.
     *
     * @param bool $allowEmpty
     * @return $this
     */
    protected function overwriteAllowEmpty(bool $allowEmpty): NotEmpty
    {
        $this->allowEmpty = $allowEmpty;
        return $this;
    }

    /**
     * Set the callback to execute to determine whether or not the rule should allow empty.
     *
     * @param callable $allowEmptyCallback
     * @return $this
     */
    protected function setAllowEmptyCallback(callable $allowEmptyCallback): NotEmpty
    {
        $this->allowEmptyCallback = $allowEmptyCallback;
        return $this;
    }


}