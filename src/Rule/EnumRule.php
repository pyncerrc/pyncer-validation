<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

class EnumRule implements RuleInterface
{
    private array $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid enum value specified.');
        }

        return $this->clean($value);
    }
    public function isValid(mixed $value): bool
    {
        if (!in_array(strval($value), $this->values, true)) {
            return false;
        }

        return true;
    }
    public function clean(mixed $value): mixed
    {
        return strval($value);
    }
    public function getError(): ?string
    {
        return 'invalid';
    }
}
