<?php

namespace Lib\Application\Validation\Rules;

class BetweenRule extends ValidationRule
{
    public const MESSAGE_BETWEEN = 'between';
    public const MESSAGE_BETWEEN_STRING = 'between.string';
    public const MESSAGE_BETWEEN_ARRAY = 'between.array';

    public function __construct(array $args)
    {
        parent::__construct('between', self::parseArguments($args, self::TYPE_INT));
        
        if (count($args) != 2) {
            throw new \Exception('A rule "between" must have $min and $max arguments!');
        }

        if (!is_int($this->arguments[0]) || !is_int($this->arguments[1])) {
            throw new \Exception('A rule "between" must have $min and $max arguments as integer!');
        }
    }

    public function validate(string $attribute, mixed $value)
    {
        [$min, $max] = $this->arguments;
        
        if (is_string($value)) {
            return is_between(strlen($value), $min, $max) ?: $this->fail(self::MESSAGE_BETWEEN_STRING);
        } else if (is_array($value)) {
            return is_between(count($value), $min, $max) ?: $this->fail(self::MESSAGE_BETWEEN_ARRAY);
        } else if ($value === null || !is_between($value, $min, $max)) {
            return $this->fail(self::MESSAGE_BETWEEN);
        }

        return true;
    }

    protected function messages()
    {
        [$min, $max] = $this->arguments;

        return [
            self::MESSAGE_BETWEEN => 'The :field must be between ' . $min . ' and ' .  $max . '.',
            self::MESSAGE_BETWEEN_STRING => 'The :field must have length between ' . $min . ' and ' .  $max . ' characters.',
            self::MESSAGE_BETWEEN_ARRAY => 'The :field must have size between ' . $min . ' and ' .  $max . '.',
        ];
    }
}