<?php

namespace Lib\Application\Validation\Rules;

class EndsWithRule extends ValidationRule
{
    public const MESSAGE_ENDS_WITH = 'ends_with';

    public function __construct(array $args)
    {
        parent::__construct('ends_with', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || !str_ends_with($value, $this->arguments[0])) {
            return $this->fail(self::MESSAGE_ENDS_WITH);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_ENDS_WITH => 'The ":field" must end with "' . $this->arguments[0] . '".',
        ];
    }
}