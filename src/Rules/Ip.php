<?php

declare(strict_types=1);

namespace NspTeam\Component\Validator\Rules;

class Ip extends AbstractRule
{
    /**
     * A constant that will be used when the value does not represent a string.
     */
    public const INVALID_IP = 'Ip::INVALID_IP';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_IP => '{{ name }} 无效Ip',
    ];

    public function validate($value): bool
    {
        if (!is_string($value)) {
            return $this->error(self::INVALID_IP);
        }

        if ($this->validateIPv4($value) || $this->validateIPv6($value)) {
            return $this->error(self::INVALID_IP);
        }
        return true;
    }

    /**
     * Validates an IPv4 address
     *
     * @param string $value
     * @return bool
     */
    protected function validateIPv4(string $value): bool
    {
        if (preg_match('/^([01]{8}\.){3}[01]{8}\z/i', $value)) {
            // binary format  00000000.00000000.00000000.00000000
            $value = bindec(substr($value, 0, 8)) . '.' . bindec(substr($value, 9, 8)) . '.'
                . bindec(substr($value, 18, 8)) . '.' . bindec(substr($value, 27, 8));
        } elseif (preg_match('/^(\d{3}\.){3}\d{3}\z/i', $value)) {
            // octet format 777.777.777.777
            $value = (int) substr($value, 0, 3) . '.' . (int) substr($value, 4, 3) . '.'
                . (int) substr($value, 8, 3) . '.' . (int) substr($value, 12, 3);
        } elseif (preg_match('/^([0-9a-f]{2}\.){3}[0-9a-f]{2}\z/i', $value)) {
            // hex format ff.ff.ff.ff
            $value = hexdec(substr($value, 0, 2)) . '.' . hexdec(substr($value, 3, 2)) . '.'
                . hexdec(substr($value, 6, 2)) . '.' . hexdec(substr($value, 9, 2));
        }

        $ip2long = ip2long($value);
        if ($ip2long === false) {
            return false;
        }

        return ($value === long2ip($ip2long));
    }

    /**
     * Validates an IPv6 address
     *
     * @param string $value Value to check against
     * @return bool True when $value is a valid ipv6 address
     *                 False otherwise
     */
    protected function validateIPv6(string $value): bool
    {
        if (strlen($value) < 3) {
            return $value === '::';
        }

        if (strpos($value, '.')) {
            $lastColon = strrpos($value, ':');
            if (! ($lastColon && $this->validateIPv4(substr($value, $lastColon + 1)))) {
                return false;
            }
            $value = substr($value, 0, $lastColon) . ':0:0';
        }

        if (strpos($value, '::') === false) {
            return preg_match('/\A(?:[a-f0-9]{1,4}:){7}[a-f0-9]{1,4}\z/i', $value);
        }

        $colonCount = substr_count($value, ':');
        if ($colonCount < 8) {
            return preg_match('/\A(?::|(?:[a-f0-9]{1,4}:)+):(?:(?:[a-f0-9]{1,4}:)*[a-f0-9]{1,4})?\z/i', $value);
        }

        // special case with ending or starting double colon
        if ($colonCount === 8) {
            return preg_match('/\A(?:::)?(?:[a-f0-9]{1,4}:){6}[a-f0-9]{1,4}(?:::)?\z/i', $value);
        }

        return false;
    }
}