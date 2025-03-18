<?php

namespace Lib\Application\Validation\Rules;

class NumericRule extends ValidationRule
{
    public const MESSAGE_NUMERIC = 'numeric';

    public function __construct()
    {
        parent::__construct('numeric', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_numeric($value)) {
            return $this->fail(self::MESSAGE_NUMERIC);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_NUMERIC => 'The ":field" must be numeric.',
        ];
    }
}