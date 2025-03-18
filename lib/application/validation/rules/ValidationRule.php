<?php

namespace Lib\Application\Validation\Rules;

abstract class ValidationRule
{
    protected const TYPE_INT = 1;
    protected const TYPE_STR = 2;
    protected const TYPE_BOOLEAN = 3;

    protected string $keyword;
    protected array $arguments;
    private  string $message = '';

    public function __construct(string $keyword, array $args)
    {
        $this->keyword = $keyword;
        $this->arguments = $args;
    }

    public function keyword()
    {
        return $this->keyword;
    }

    public function message()
    {
        return [$this->message, $this->messages()[$this->message] ?? ''];
    }

    public function fail(string $messageKey)
    {
        $this->message = $messageKey;
        return false;
    }

    public function __tostring()
    {
        $text = get_class_name($this);
        $text .= " {";
        $text .= " \"keyword\" => \"$this->keyword\",";
        $text .= " \"arguments\" => [" . implode(', ', $this->arguments) . "]";
        $text .= " }";

        return $text;
    }

    protected static function parseArguments(array $args, int $type = self::TYPE_STR)
    {
        return array_map(function ($arg) use($type) {
            if ($type === self::TYPE_INT) {
                $var = filter_var($arg, FILTER_VALIDATE_INT);
            } else if ($type === self::TYPE_BOOLEAN) {
                $var = in_array($arg, [0, 1, "0", "1", true, false], true) ? !!$arg : $arg;
            } else {
                $var = $arg;
            }

            return $var;
            

        }, $args);
    }

    /**
     * Validates a rule. If rule fails it will set up message.
     * 
     * @param string $attribute name of attribute
     * @param mixed $value value of attribute
     * @return bool
     */
    abstract public function validate(string $attribute, mixed $value);

    /**
     * Gets messages with unique keys.
     * 
     * @return array
     */
    abstract protected function messages();
}

// Rules
// -----
// "boolean"            => hodnota musi byt boolean
// "contains:foo,bar"   => hodnota musi obsahovat vsetky string
// "date"               => hodnota musi byt datum
// "date_format:Y-m-d"  => datum musi byt daneho formatu
// "email"              => "hodnota musi byt email"
// "ends_with:foo,bar"  => "hodnota musi koncit jednym zo slov
// "integer"            => hodnota musi byt integer
// "json"               => hodnota musi byt json
// "lowercase"          => text musi byt malymi pismenami
// "max:255"            => hodnota musi byt mensia alebo rovna ako 255
// "min:255"            => hodnota musi byt vacsia alebo rovna ako 255
// "numeric"            => hodnota musi byt cislo
// "required"           => hodnota je povinna
// "same:field"         => hodntoa musi mat rovnaku hodnotu ako pole "field"
// "starts_with:foo,bar"=> hodnota musi zacinat s jednou z hodnot
// "string              => hodnota musi byt text
// "uppercase"          => hodnota musi byt v uppercase formate
