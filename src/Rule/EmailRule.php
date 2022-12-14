<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

use function Pyncer\nullify as pyncer_nullify;

class EmailRule implements RuleInterface
{
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Empty value specified.');
        }

        return $this->clean($value);
    }

    public function isValid(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }

        return false;
    }

    public function clean(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return strval($value);
    }

    public function getError(): ?string
    {
        return 'invalid';
    }
}
