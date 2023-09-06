<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;
use Stringable;

use function is_string;
use function Pyncer\nullify as pyncer_nullify;
use function trim;

class RequiredRule implements RuleInterface
{
    /**
     * @param bool $allowWhitespace When true, surrounding whitespace will
     *      be allowed.
     */
    public function __construct(
        private bool $allowWhitespace = false,
    ) {}

    /**
     * @inheritdoc
     */
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Empty value specified.');
        }

        return $this->clean($value);
    }

    /**
     * @inheritdoc
     */
    public function isValid(mixed $value): bool
    {
        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (is_string($value) && !$this->allowWhitespace) {
            $value = trim($value);
        }

        return (pyncer_nullify($value) !== null);
    }

    /**
     * @inheritdoc
     */
    public function clean(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function isValidAndClean(mixed $value): bool
    {
        if (!$this->isValid($value)) {
            return false;
        }

        if ($this->clean($value) !== $value) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError(): ?string
    {
        return 'required';
    }
}
