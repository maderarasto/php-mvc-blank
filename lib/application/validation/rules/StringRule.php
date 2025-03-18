<?php

namespace Lib\Application\Validation\Rules;

class StringRule extends ValidationRule
{
    public const MESSAGE_STRING = 'string';

    public function __construct()
    {
        parent::__construct('string', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value)) {
            return $this->fail(self::MESSAGE_STRING);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_STRING => 'The ":field" must be string.',
        ];
    }
}