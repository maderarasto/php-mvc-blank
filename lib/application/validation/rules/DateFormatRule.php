<?php

namespace Lib\Application\Validation\Rules;

use DateTime;

class DateFormatRule extends ValidationRule
{
    public const MESSAGE_DATE_FORMAT = 'date_format';

    public function __construct($args)
    {
        parent::__construct('date_format', $args);
    }

    public function validate(string $attribute, mixed $value)
    {
        if (!is_string($value) || strtotime($value) === false) {
            return $this->fail(self::MESSAGE_DATE_FORMAT);
        }

        if (DateTime::createFromFormat($this->arguments[0], $value) === false) {
            return $this->fail(self::MESSAGE_DATE_FORMAT);
        }
        
        return true;
    }

    protected function messages()
    {
        return [
            self::MESSAGE_DATE_FORMAT => 'The ":field" must have format "' . $this->arguments[0] . '".',
        ];
    }
}