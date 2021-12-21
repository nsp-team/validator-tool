<?php

namespace NspTeam\Component\Validator\Rules;

class Datetime extends AbstractRule
{
    /**
     * A constant that will be used when an invalid date/time is passed.
     */
    public const INVALID_VALUE = 'DateTime::INVALID_VALUE';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_VALUE => '{{ name }} 必须是有效日期',
    ];

    /**
     * @var string
     */
    protected $format;

    public function __construct($format = null)
    {
        $this->format = $format;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (!($this->datetime($value, $this->format) instanceof \DateTime)) {
            return $this->error(self::INVALID_VALUE);
        }
        return true;
    }

    protected function datetime(string $time, ?string $format = null)
    {
        if ($format !== null) {
            $dateTime = \DateTime::createFromFormat($format, $time);

            if ($dateTime instanceof \DateTime) {
                return $this->checkDate($dateTime, $format, $time);
            }
            return false;
        }

        return @date_create($time);
    }

    protected function checkDate(\DateTime $dateTime, string $format, string $time)
    {
        $equal = $dateTime->format($format) === $time;

        if ($equal && $dateTime->getLastErrors()['warning_count'] === 0) {
            return $dateTime;
        }
        return false;
    }
}