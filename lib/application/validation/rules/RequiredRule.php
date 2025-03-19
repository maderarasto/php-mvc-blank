<?php

namespace Lib\Application\Validation\Rules;

class RequiredRule extends ValidationRule
{
    public const MESSAGE_REQUIRED = 'required';

    public function __construct()
    {
        parent::__construct('required', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if ($value === null) {
            return $this->fail(self::MESSAGE_REQUIRED);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_REQUIRED => 'The ":field" is required.',
        ];
    }
}