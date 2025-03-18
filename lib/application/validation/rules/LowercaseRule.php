<?php

namespace Lib\Application\Validation\Rules;

class LowercaseRule extends ValidationRule
{
    public const MESSAGE_LOWERCASE = 'lowercase';

    public function __construct()
    {
        parent::__construct('lowercase', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || strtolower($value) !== $value) {
            return $this->fail(self::MESSAGE_LOWERCASE);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_LOWERCASE => 'The ":field" must be in lowercase.',
        ];
    }
}