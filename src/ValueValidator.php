<?php
namespace Pyncer\Validation;

use Pyncer\Validation\Rule\RuleInterface;

class ValueValidator
{
    protected array $rules = [];

    public function __construct()
    {}

    public function addRules(RuleInterface ...$rules): static
    {
        $this->rules = array_merge($this->rules, $rules);
        return $this;
    }

    public function hasRules(): bool
    {
        return (count($this->rules) > 0);
    }

    public function clearRules(): static
    {
        $this->rules = [];
        return $this;
    }

    public function defend(mixed $value): mixed
    {
        foreach ($this->rules as $rule) {
            $value = $rule->defend($value);
        }

        return $value;
    }

    public function isValid(mixed $value): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->isValid($value)) {
                return false;
            }

            $value = $rule->clean($value);
        }

        return true;
    }

    public function clean(mixed $value): mixed
    {
        foreach ($this->rules as $rule) {
            $value = $rule->clean($value);
        }

        return $value;
    }

    public function getError(mixed $value): ?string
    {
        foreach ($this->rules as $rule) {
            if (!$rule->isValid($value)) {
                return $rule->getError();
            }
        }

        return null;
    }
}
