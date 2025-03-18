<?php

namespace Lib\Application\Validation\Rules;

class BooleanRule extends ValidationRule
{
    protected const ALLOWED_VALUES = [0, 1, "0", "1", true, false];
    public const MESSAGE_BOOLEAN = 'boolean';

    public function __construct()
    {
        parent::__construct('boolean', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        // Cast to boolean if $values is from allowed boolean values
        $var = in_array($value, self::ALLOWED_VALUES, true) ? !!$value : $value;

        if (!is_bool($var)) {
            return $this->fail(self::MESSAGE_BOOLEAN);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_BOOLEAN => 'The :field must be boolean.',
        ];
    }
}