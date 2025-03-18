<?php

namespace Lib\Application\Validation\Rules;

class EmailRule extends ValidationRule
{
    public const MESSAGE_EMAIL = 'email';

    public function __construct()
    {
        parent::__construct('email', []);
    }

    public function validate(string $attribute, mixed $value)
    {   
        if (!is_string($value)) {
            return $this->fail(self::MESSAGE_EMAIL);
        }
        
        if (!preg_match('/[a-zA-Z0-9._%+-]+[@][a-zA-Z0-9.-]+[.][a-zA-Z]{2,}/m', $value)) {
            return $this->fail(self::MESSAGE_EMAIL);
        }
        
        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_EMAIL => 'The ":field" must be email.',
        ];
    }
}