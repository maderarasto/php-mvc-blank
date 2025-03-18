<?php

namespace Lib\Application\Validation\Rules;

class StartsWithRule extends ValidationRule
{
    public const MESSAGE_ENDS_WITH = 'starts_with';

    public function __construct(array $args)
    {
        parent::__construct('starts_with', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || !str_starts_with($value, $this->arguments[0])) {
            return $this->fail(self::MESSAGE_ENDS_WITH);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_ENDS_WITH => 'The ":field" must start with "' . $this->arguments[0] . '".',
        ];
    }
}