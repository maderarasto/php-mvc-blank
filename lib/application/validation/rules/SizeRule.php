<?php

namespace Lib\Application\Validation\Rules;

// size.integer = '';
// size.string = '';
// size.array = '';

class SizeRule extends ValidationRule
{
    public const MESSAGE_SIZE = 'size';
    public const MESSAGE_SIZE_STRING = 'size.string';
    public const MESSAGE_SIZE_ARRAY = 'size.array';

    public function __construct(array $args)
    {
        parent::__construct('size', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (is_string($value) && strlen($value) != $this->arguments[0]) {
            return $this->fail(self::MESSAGE_SIZE_STRING);
        } else if (is_array($value) && count($value) != $this->arguments[0]) {
            return $this->fail(self::MESSAGE_SIZE_ARRAY);
        } else if ($value != $this->arguments[0]) {
            return $this->fail(self::MESSAGE_SIZE);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_SIZE => 'The :field must be ' . $this->arguments[0] . '.',
            self::MESSAGE_SIZE_STRING => 'The :field must be ' . $this->arguments[0] . ' characters long.',
            self::MESSAGE_SIZE_ARRAY => 'The :field must have size of ' . $this->arguments[0] . ' items.',
        ];
    }
}