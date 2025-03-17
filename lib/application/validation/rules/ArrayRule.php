<?php

namespace Lib\Application\Validation\Rules;

class ArrayRule extends ValidationRule
{
    public const MESSAGE_ARRAY = 'array';
    public const MESSAGE_ARRAY_KEYS = 'array.keys';

    public function __construct(array $keys)
    {
        parent::__construct('array', $keys);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_array($value)) {
            return $this->fail(self::MESSAGE_ARRAY);
        } else if (!$this->_containsKeys($value)) {
            return $this->fail(self::MESSAGE_ARRAY_KEYS);
        }

        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_ARRAY => 'The ":field" must be array.',
            self::MESSAGE_ARRAY_KEYS => 'The ":field" must have required keys: ' . implode(', ', array_map(function ($arg) {
                return "\"$arg\"";
            }, $this->arguments))
        ];
    }

    private function _containsKeys(array $array)
    {
        $flippedKeys = array_flip($this->arguments);
        $diffKeys = array_diff_key($flippedKeys, $array);

        return empty($diffKeys);
    }
}