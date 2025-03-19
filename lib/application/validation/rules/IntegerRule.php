<?php

namespace Lib\Application\Validation\Rules;

class IntegerRule extends ValidationRule
{
    public const MESSAGE_INTEGER = 'integer';

    public function __construct()
    {
        parent::__construct('integer', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        $var = filter_var($value, FILTER_VALIDATE_INT);

        if (!is_integer($var)) {
            return $this->fail(self::MESSAGE_INTEGER);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_INTEGER => 'The ":field" must be integer.',
        ];
    }
}