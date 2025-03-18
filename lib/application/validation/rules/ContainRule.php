<?php

namespace Lib\Application\Validation\Rules;

class ContainRule extends ValidationRule
{
    public const MESSAGE_CONTAIN = 'contain';

    public function __construct(array $args)
    {
        parent::__construct('contain', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || !str_contains($value, $this->arguments[0])) {
            return $this->fail(self::MESSAGE_CONTAIN);
        }
        
        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_CONTAIN => 'The ":field" must contain string "' . $this->arguments[0] . '".',
        ];
    }
}