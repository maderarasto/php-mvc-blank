<?php

namespace Lib\Application\Validation\Rules;

class DateRule extends ValidationRule
{
    public const MESSAGE_DATE = 'date';

    public function __construct()
    {
        parent::__construct('date', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || strtotime($value) === false) {
            return $this->fail(self::MESSAGE_DATE);
        }
        
        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_DATE => 'The ":field" must be date string.',
        ];
    }
}