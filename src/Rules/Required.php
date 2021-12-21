<?php

namespace NspTeam\Component\Validator\Rules;

use NspTeam\Component\Validator\Value\Container;

/**
 * 这个类负责检查是否设置了必需的值
 */
class Required extends AbstractRule
{
    use CallbackTrait;

    /**
     * The error code when a required field doesn't exist.
     */
    public const NON_EXISTENT_KEY = 'Required::NON_EXISTENT_KEY';

    /**
     * The templates for the possible messages this validator can return.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NON_EXISTENT_KEY => '请传入必填项 {{ key }} '
    ];

    /**
     * Denotes whether or not the chain should be stopped after this rule.
     *
     * @var bool
     */
    protected $shouldBreak = false;

    /**
     * Indicates if the value is required.
     *
     * @var bool
     */
    protected $required;

    /**
     * Optionally contains a callable to overwrite the required requirement on time of validation.
     *
     * @var callable
     */
    protected $requiredCallback;

    /**
     * Contains the input container.
     *
     * @var Container
     */
    protected $input;


    public function __construct(bool $required)
    {
        $this->required = $required;
    }

    /**
     * @inheritdoc
     */
    public function shouldBreakChain():bool
    {
        return $this->shouldBreak;
    }

    /**
     * Does nothing, because validity is determined in isValid.
     *
     * @inheritdoc
     */
    public function validate($value):bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isValid($key, Container $input):bool
    {
        $this->input = $input;
        $this->shouldBreak = false;
        $this->required = $this->isRequired($input);

        if (!$input->has($key)) {
            $this->shouldBreak = true;
            if ($this->required) {
                return $this->error(self::NON_EXISTENT_KEY);
            }
        }

        return $this->validate($input->get($key));
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageParameters(): array
    {
        return array_merge(parent::getMessageParameters(), [
            'required' => $this->required,
            'callback' => $this->getCallbackAsString($this->requiredCallback)
        ]);
    }

    /**
     * 确认值是否必填.
     *
     * @param Container $input
     * @return bool
     */
    protected function isRequired(Container $input): bool
    {
        if (isset($this->requiredCallback)) {
            $this->required = call_user_func($this->requiredCallback, $input->getArrayCopy());
        }
        return $this->required;
    }

    /**
     * Set a callable to potentially alter the required requirement at the time of validation.
     *
     * This may be incredibly useful for conditional validation.
     *
     * @param callable|bool $required
     * @return $this
     */
    public function setRequired($required): Required
    {
        if (is_callable($required)) {
            return $this->setRequiredCallback($required);
        }

        return $this->overwriteRequired($required);
    }

    /**
     * Overwrite the required requirement after instantiation of this object.
     *
     * @param bool $required
     * @return $this
     */
    protected function overwriteRequired(bool $required): Required
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Set the required callback, and return $this.
     *
     * @param callable $requiredCallback
     * @return $this
     */
    protected function setRequiredCallback(callable $requiredCallback): Required
    {
        $this->requiredCallback = $requiredCallback;
        return $this;
    }
}