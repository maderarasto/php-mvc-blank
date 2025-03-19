<?php

namespace Lib\Application\Validation\Rules;

class MinimumRule extends ValidationRule
{
    public const MESSAGE_MIN = 'min';
    public const MESSAGE_MIN_STRING = 'min.string';
    public const MESSAGE_MIN_ARRAY = 'min.array';

    public function __construct(array $args)
    {
        parent::__construct('min', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (is_numeric($value)) {
            return $value >= $this->arguments[0] ?: $this->fail(self::MESSAGE_MIN);
        } else if (is_string($value)) {
            return strlen($value) >= $this->arguments[0] ?: $this->fail(self::MESSAGE_MIN_STRING);
        } else if (is_array($value)) {
            return count($value) >= $this->arguments[0] ?: $this->fail(self::MESSAGE_MIN_ARRAY);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_MIN => 'The :field must be at least ' . $this->arguments[0] . '.',
            self::MESSAGE_MIN_STRING => 'The :field must be at least ' . $this->arguments[0] . ' characters long.',
            self::MESSAGE_MIN_ARRAY => 'The :field must have at least ' . $this->arguments[0] . ' items.',
        ];
    }
}