<?php

namespace NspTeam\Component\Validator\Rules;

class InArray extends AbstractRule
{
    /**
     * A constant that will be used when the value is not in the array without strict checking.
     */
    public const NOT_IN_ARRAY = 'InArray::NOT_IN_ARRAY';


    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_IN_ARRAY => '{{ name }} 必须在已定义的数据集合中',
    ];

    /**
     * The array that contains the values to check.
     *
     * @var array
     */
    protected $array = [];

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (in_array($value, $this->array, true)) {
            return true;
        }
        return $this->error(self::NOT_IN_ARRAY);
    }

    /**
     * @inheritDoc
     */
    protected function getMessageParameters():array
    {
        $quote = static function ($value) {
            return '"' . $value . '"';
        };

        return array_merge(parent::getMessageParameters(), [
            'values' => implode(', ', array_map($quote, $this->array))
        ]);
    }
}