<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

// TODO: make this actually do something
class DateRule implements RuleInterface
{
    public function __construct(
        private bool $allowNull = false,
    ) {}

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
        if ($this->allowNull && $value === null) {
            return null;
        }

        return $value;
    }

    public function getError(): ?string
    {
        return null;
    }
}
