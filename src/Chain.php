<?php

namespace NspTeam\Component\Validator;

use NspTeam\Component\Validator\Output\Structure;
use NspTeam\Component\Validator\Output\Subject;
use NspTeam\Component\Validator\Rules\AbstractRule;
use NspTeam\Component\Validator\Rules\Alnum;
use NspTeam\Component\Validator\Rules\Alpha;
use NspTeam\Component\Validator\Rules\Between;
use NspTeam\Component\Validator\Rules\Callback;
use NspTeam\Component\Validator\Rules\Datetime;
use NspTeam\Component\Validator\Rules\Digits;
use NspTeam\Component\Validator\Rules\Each;
use NspTeam\Component\Validator\Rules\Email;
use NspTeam\Component\Validator\Rules\Equal;
use NspTeam\Component\Validator\Rules\GreaterThan;
use NspTeam\Component\Validator\Rules\InArray;
use NspTeam\Component\Validator\Rules\IsArray;
use NspTeam\Component\Validator\Rules\IsBoolean;
use NspTeam\Component\Validator\Rules\IsFloat;
use NspTeam\Component\Validator\Rules\IsInteger;
use NspTeam\Component\Validator\Rules\IsJson;
use NspTeam\Component\Validator\Rules\IsNumeric;
use NspTeam\Component\Validator\Rules\IsPhone;
use NspTeam\Component\Validator\Rules\IsString;
use NspTeam\Component\Validator\Rules\Length;
use NspTeam\Component\Validator\Rules\LengthBetween;
use NspTeam\Component\Validator\Rules\LessThan;
use NspTeam\Component\Validator\Rules\NotEmpty;
use NspTeam\Component\Validator\Rules\Regex;
use NspTeam\Component\Validator\Rules\Required;
use NspTeam\Component\Validator\Rules\Url;
use NspTeam\Component\Validator\Value\Container;

class Chain
{
    /**
     * 要验证的key
     * @var string
     */
    protected $key;

    /**
     * 错误消息中使用的name
     * @var string
     */
    protected $name;

    /**
     * The array of all rules for this chain.
     * @var AbstractRule[]
     */
    protected $rules = [];

    /**
     * The message stack to append messages to.
     * @var MessageStack
     */
    protected $messageStack;

    /**
     * Construct the chain.
     *
     * @param string $key
     * @param string|null $name
     * @param bool $required
     * @param bool $allowEmpty
     */
    public function __construct(string $key, ?string $name, bool $required, bool $allowEmpty)
    {
        $this->key = $key;
        $this->name = $name;

        $this->addRule(new Required($required));
        $this->addRule(new NotEmpty($allowEmpty));
    }

    protected function addRule(AbstractRule $rule): Chain
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Returns the first rule, which is always the required rule.
     *
     * @return AbstractRule|Required
     */
    private function getRequiredRule(): Required
    {
        return $this->rules[0];
    }

    /**
     * Returns the second rule, which is always the allow empty rule.
     *
     * @return AbstractRule|NotEmpty
     */
    private function getNotEmptyRule(): NotEmpty
    {
        return $this->rules[1];
    }

    /**
     * Set a callable or boolean value which may be used to alter the required requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable|bool $required
     * @return $this
     */
    public function required($required): Chain
    {
        $this->getRequiredRule()->setRequired($required);
        return $this;
    }

    /**
     * Set a callable or boolean value which may be used to alter the allow empty requirement on validation time.
     *
     * This may be incredibly helpful when doing conditional validation.
     *
     * @param callable|bool $allowEmpty
     * @return $this
     */
    public function allowEmpty($allowEmpty): Chain
    {
        $this->getNotEmptyRule()->setAllowEmpty($allowEmpty);
        return $this;
    }

    /**
     * Validates the values in the $values array and appends messages to $messageStack. Returns the result as a bool.
     *
     * @param MessageStack $messageStack
     * @param Container $input
     * @param Container $output
     * @return bool
     */
    public function validate(MessageStack $messageStack, Container $input, Container $output): bool
    {
        $valid = true;
        foreach ($this->rules as $rule) {
            $rule->setMessageStack($messageStack);
            $rule->setParameters($this->key, $this->name);

            $valid = $rule->isValid($this->key, $input) && $valid;

            if (($valid === false && $rule->shouldBreakChainOnError()) || $rule->shouldBreakChain()) {
                break;
            }
        }

        if ($valid && $input->has($this->key)) {
            $output->set($this->key, $input->get($this->key));
        }
        return $valid;
    }

    /**
     * Attach a representation of this Chain to the Output\Structure $structure.
     *
     * @internal
     * @param Structure $structure
     * @param MessageStack $messageStack
     * @return Structure
     */
    public function output(Structure $structure, MessageStack $messageStack): Structure
    {
        $subject = new Subject($this->key, $this->name);

        foreach ($this->rules as $rule) {
            $rule->output($subject, $messageStack);
        }

        $structure->addSubject($subject);

        return $structure;
    }

    /**
     * Overwrite the default __clone behaviour to make sure the rules are cloned too.
     */
    public function __clone()
    {
        $rules = [];
        foreach ($this->rules as $rule) {
            $rules[] = clone $rule;
        }
        $this->rules = $rules;
    }

######################################## src/Rules/*Rule.php ###########################################################

    /**
     * 验证该值表示一个浮点数。
     *
     * @return $this
     */
    public function float(): Chain
    {
        return $this->addRule(new IsFloat());
    }

    /**
     * @return $this
     */
    public function alnum($allowSpace = true): Chain
    {
        return $this->addRule(new Alnum($allowSpace));
    }

    public function alpha($allowSpace = true): Chain
    {
        return $this->addRule(new Alpha($allowSpace));
    }

    public function between($min, $max): Chain
    {
        return $this->addRule(new Between($min, $max));
    }

    public function bool(): Chain
    {
        return $this->addRule(new IsBoolean());
    }

    public function datetime($format = null): Chain
    {
        return $this->addRule(new Datetime($format));
    }

    public function digits(): Chain
    {
        return $this->addRule(new Digits());
    }

    public function each(callable $callback): Chain
    {
        return $this->addRule(new Each($callback));
    }

    public function email(): Chain
    {
        return $this->addRule(new Email());
    }

    public function equals($value): Chain
    {
        return $this->addRule(new Equal($value));
    }

    public function greaterThan($value): Chain
    {
        return $this->addRule(new GreaterThan($value));
    }

    public function inArray(array $array): Chain
    {
        return $this->addRule(new InArray($array));
    }

    public function isArray(): Chain
    {
        return $this->addRule(new IsArray());
    }

    public function integer($strict = false): Chain
    {
        return $this->addRule(new IsInteger($strict));
    }

    public function json(): Chain
    {
        return $this->addRule(new IsJson());
    }

    public function length($length): Chain
    {
        return $this->addRule(new Length($length));
    }

    public function lengthBetween($min, $max): Chain
    {
        return $this->addRule(new LengthBetween($min, $max));
    }

    public function lessThan($value): Chain
    {
        return $this->addRule(new LessThan($value));
    }

    public function numeric(): Chain
    {
        return $this->addRule(new IsNumeric());
    }

    public function phone(): Chain
    {
        return $this->addRule(new IsPhone());
    }

    public function string(): Chain
    {
        return $this->addRule(new IsString());
    }

    public function url(array $schemes = []): Chain
    {
        return $this->addRule(new Url($schemes));
    }

    public function regex(string $regex): Chain
    {
        return $this->addRule(new Regex($regex));
    }

    public function callback(callable $callback): Chain
    {
        return $this->addRule(new Callback($callback));
    }

    /**
     * Mount a rule object onto this chain.
     *
     * @param AbstractRule $rule
     * @return $this
     */
    public function mount(AbstractRule $rule): Chain
    {
        return $this->addRule($rule);
    }
}