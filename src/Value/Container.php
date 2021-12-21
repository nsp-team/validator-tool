<?php

namespace NspTeam\Component\Validator\Value;

/**
 * This class is used to wrap both input as output arrays.
 */
class Container
{
    /**
     * Contains the values (either input or output).
     *
     * @var array
     */
    protected $values = [];

    /**
     * Construct the Value\Container.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * Determines whether or not the container has a value for key $key.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return $this->traverse($key, false);
    }

    /**
     * Returns the value for the key $key, or null if the value doesn't exist.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->traverse($key, true);
    }

    /**
     * Set the value of $key to $value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value): Container
    {
        if (strpos($key, '.') !== false) {
            return $this->setTraverse($key, $value);
        }
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * Returns a plain array representation of the Value\Container object.
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->values;
    }

    /**
     * 使用点表示法遍历键。根据第二个参数，如果它已设置,它将返回value值
     *
     * @param string $key
     * @param bool $returnValue
     * @return mixed
     */
    protected function traverse(string $key, bool $returnValue = true)
    {
        $value = $this->values;
        foreach (explode('.', $key) as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return false;
            }
            $value = $value[$part];
        }
        return $returnValue ? $value : true;
    }

    /**
     * Uses dot-notation to set a value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setTraverse(string $key, $value): Container
    {
        $parts = explode('.', $key);
        $ref = &$this->values;

        foreach ($parts as $i => $part) {
            if ((!isset($ref[$part]) || !is_array($ref[$part])) && $i < (count($parts) - 1)) {
                $ref[$part] = [];
            }
            $ref = &$ref[$part];
        }

        $ref = $value;
        return $this;
    }
}