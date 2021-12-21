<?php

namespace NspTeam\Component\Validator\Rules;

class Url extends AbstractRule
{
    /**
     * A constant that will be used if the value is not a valid URL.
     */
    public const INVALID_URL = 'Url::INVALID_URL';

    /**
     * A constant that will be used if the value is not in a white-listed scheme.
     */
    public const INVALID_SCHEME = 'Url::INVALID_SCHEME';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_URL => '{{ name }} 必须是有效的URL',
        self::INVALID_SCHEME => '{{ name }} must have one of the following schemes: {{ schemes }}',
    ];

    /**
     * @var array
     */
    protected $schemes = [];

    /**
     * Construct the URL rule.
     *
     * @param array $schemes
     */
    public function __construct(array $schemes = [])
    {
        $this->schemes = $schemes;
    }

    /**
     * @inheritDoc
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        // make sure the length is limited to avoid DOS attacks
        if (is_string($value) && strlen($value) < 2000) {
            $url = filter_var($value, FILTER_VALIDATE_URL);

            if ($url !== false) {
                return $this->validateScheme($value);
            }
        }

        return $this->error(self::INVALID_URL);
    }

    /**
     * @inheritDoc
     * @return array
     */
    protected function getMessageParameters(): array
    {
        return array_merge(parent::getMessageParameters(), [
            'schemes' => implode(', ', $this->schemes)
        ]);
    }

    protected function validateScheme($value): bool
    {
        $scheme = parse_url($value, PHP_URL_SCHEME);
        if (count($this->schemes) > 0 && !in_array($scheme, $this->schemes, true)) {
            return $this->error(self::INVALID_SCHEME);
        }
        return true;
    }
}