<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

class MaxLengthRule implements RuleInterface
{
    private $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid string length specified.');
        }

        return $this->clean($value);
    }
    public function isValid(mixed $value): bool
    {
        $value = strval($value);

        return (strlen($value) <= $this->length);
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
