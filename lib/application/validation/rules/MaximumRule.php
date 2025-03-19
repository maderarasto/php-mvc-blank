<?php

namespace Lib\Application\Validation\Rules;

class MaximumRule extends ValidationRule
{
    public const MESSAGE_MAX = 'max';
    public const MESSAGE_MAX_STRING = 'max.string';
    public const MESSAGE_MAX_ARRAY = 'max.array';

    public function __construct(array $args)
    {
        parent::__construct('max', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (is_numeric($value)) {
            return $value <= $this->arguments[0] ?: $this->fail(self::MESSAGE_MAX);
        } else if (is_string($value)) {
            return strlen($value) <= $this->arguments[0] ?: $this->fail(self::MESSAGE_MAX_STRING);
        } else if (is_array($value)) {
            return count($value) <= $this->arguments[0] ?: $this->fail(self::MESSAGE_MAX_ARRAY);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_MAX => 'The :field must be at most ' . $this->arguments[0] . '.',
            self::MESSAGE_MAX_STRING => 'The :field must be at most ' . $this->arguments[0] . ' characters long.',
            self::MESSAGE_MAX_ARRAY => 'The :field must have at most ' . $this->arguments[0] . ' items.',
        ];
    }
}