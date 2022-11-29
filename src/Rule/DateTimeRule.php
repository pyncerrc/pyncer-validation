<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

// TODO: make this actually do something
class DateTimeRule implements RuleInterface
{
    public function defend(mixed $value): mixed
    {
        return $this->clean($value);
    }
    public function isValid(mixed $value): bool
    {
        return true;
    }
    public function clean(mixed $value): mixed
    {
        return $value;
    }
    public function getError(): ?string
    {
        return null;
    }
}
