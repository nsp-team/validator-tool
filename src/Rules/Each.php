<?php

namespace NspTeam\Component\Validator\Rules;

use NspTeam\Component\Validator\Result;
use NspTeam\Component\Validator\Validator;

class Each extends AbstractRule
{
    public const NOT_AN_ARRAY = 'Each::NOT_AN_ARRAY';

    public const NOT_AN_ARRAY_ITEM = 'Each::NOT_AN_ARRAY_ITEM';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_AN_ARRAY => '{{ name }} 必须是数组',
        self::NOT_AN_ARRAY_ITEM => '每个 {{ name }} 项 必须是数组',
    ];

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (!is_array($value)) {
            return $this->error(self::NOT_AN_ARRAY);
        }

        $result = true;
        foreach ($value as $index => $innerValue) {
            if (!is_array($innerValue)) {
                return $this->error(self::NOT_AN_ARRAY_ITEM);
            }

            $result = $this->validateValue($index, $innerValue) && $result;
        }
        return $result;
    }

    /**
     * 此方法将产生一个new validator，验证一个内部数组，并返回其结果
     * @param int $index
     * @param mixed $value
     * @return bool
     */
    protected function validateValue(int $index, $value): bool
    {
        $innerValidator = new Validator();

        call_user_func($this->callback, $innerValidator);

        $result = $innerValidator->validate($value);

        if (!$result->isValid()) {
            $this->handleError($index, $result);
            return false;
        }

        return true;
    }

    /**
     * @param int $index
     * @param Result $result
     */
    protected function handleError(int $index, Result $result): void
    {
        foreach ($result->getFailures() as $failure) {
            $failure->overwriteKey(
                sprintf('%s.%s.%s', $this->key, $index, $failure->getKey())
            );

            $this->messageStack->append($failure);
        }
    }
}