<?php

namespace Lib\Application\Validation\Rules;

abstract class ValidationRule
{
    protected string $keyword;
    protected array $arguments;
    private  string $message;

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
// "array"              => hondnota musi byt pole
// "size:10"            => velkost pola musi byt velkosti 10
// "between:0,10"       => hodnota musi byt medzi 0 a 10
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
