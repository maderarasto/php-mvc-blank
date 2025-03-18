<?php

namespace Lib\Application\Validation\Rules;

class UppercaseRule extends ValidationRule
{
    public const MESSAGE_UPPERCASE = 'uppercase';

    public function __construct()
    {
        parent::__construct('uppercase', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || strtoupper($value) !== $value) {
            return $this->fail(self::MESSAGE_UPPERCASE);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_UPPERCASE => 'The ":field" must be in uppercase.',
        ];
    }
}