<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

use function Pyncer\nullify as pyncer_nullify;

class NonEmptyRule implements RuleInterface
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
        return (pyncer_nullify($value) !== null);
    }
    public function clean(mixed $value): mixed
    {
        return $value;
    }
    public function getError(): ?string
    {
        return 'required';
    }
}
