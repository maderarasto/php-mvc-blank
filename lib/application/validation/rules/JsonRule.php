<?php

namespace Lib\Application\Validation\Rules;

class JsonRule extends ValidationRule
{
    public const MESSAGE_JSON = 'json';

    public function __construct()
    {
        parent::__construct('json', []);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || !is_json($value)) {
            return $this->fail(self::MESSAGE_JSON);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_JSON => 'The ":field" must be json.',
        ];
    }
}