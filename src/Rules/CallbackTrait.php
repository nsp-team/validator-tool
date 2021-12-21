<?php

namespace NspTeam\Component\Validator\Rules;

trait CallbackTrait
{
    /**
     * Returns a string representation of a callback, if it implements the __toString method.
     *
     * @param callable|null $callback
     * @return string
     */
    protected function getCallbackAsString(?callable $callback): string
    {
        if (is_object($callback) && method_exists($callback, '__toString')) {
            return (string) $callback;
        }
        return '';
    }
}