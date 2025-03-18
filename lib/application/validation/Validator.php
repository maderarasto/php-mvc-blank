<?php

namespace Lib\Application\Validation;

use Exception;
use Lib\Application\Validation\Rules\ValidationRule;

class Validator
{
    private array $rules = [];
    private array $messages = [];
    private array $errors = [];

    private array $ruleBindings = [
        'array' => Rules\ArrayRule::class,
        'size' => Rules\SizeRule::class,
        'between' => Rules\BetweenRule::class,
        'boolean' => Rules\BooleanRule::class,
        'contain' => Rules\ContainRule::class,
        'date' => Rules\DateRule::class,
        'date_format' => Rules\DateFormatRule::class,
        'email' => Rules\EmailRule::class,
        'ends_with' => Rules\EndsWithRule::class,
        'lowercase' => Rules\LowercaseRule::class,
        'starts_with' => Rules\StartsWithRule::class,
        'uppercase' => Rules\UppercaseRule::class,
    ];

    public function __construct(array $rules, array $messages = [])
    {
        $this->rules = array_map(function ($value) {
            return explode('|', $value);
        }, $rules);
        
        $this->messages = $messages;
    }

    public function rules(array $rules)
    {
        $this->rules = array_map(function ($value) {
            return explode('|', $value);
        }, $rules);

        return $this;
    }

    public function messages(array $messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function validate(array $data)
    {
        $toValidate = array_filter($data, function ($key) {
            return array_key_exists($key, $this->rules);
        }, ARRAY_FILTER_USE_KEY);
        
        foreach ($toValidate as $field => $value) {
            $validRules = array_filter($this->rules[$field], function ($ruleKeyword) {
                [$keyword] = explode(':', $ruleKeyword);

                if (!array_key_exists($keyword, $this->ruleBindings)) {
                    return false;
                }
                
                return is_subclass_of($this->ruleBindings[$keyword], ValidationRule::class);
            });

            $rules = array_map(function ($value) {
                [$keyword, $args] = array_pad(explode(':', $value), 2, null);
                $args = explode(',', $args) ?? [];
                
                if (!$keyword) {
                    throw new Exception("Validation rule \"$keyword\" not found!");
                }

                return new $this->ruleBindings[$keyword]($args);
            }, $validRules);
            
            /** @var ValidationRule */
            foreach ($rules as $rule) {
                $valid = $rule->validate($field, $data[$field]);
                [$key, $message] = $rule->message();
                
                if (!$valid && !array_key_exists($field, $this->errors)) {
                    $this->errors[$field] = [];
                }
                
                if (!$valid) {
                    $this->errors[$field][] = $this->resolveMessage($field, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    protected function resolveMessage(string $field, ValidationRule $rule)
    {
        [$key, $message] = $rule->message();

        if (!array_key_exists("$field.$key", $this->messages)) {
            return str_replace(':field', $field, $message);
        }

        return str_replace(':field', $field, $this->messages["$field.$key"]);
    }
}