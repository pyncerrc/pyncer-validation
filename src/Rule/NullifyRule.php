<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

use function is_string;
use function Pyncer\nullify as pyncer_nullify;
use function trim;

class NullifyRule implements RuleInterface
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
        return $this->clean($value);
    }

    /**
     * @inheritdoc
     */
    public function isValid(mixed $value): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function clean(mixed $value): mixed
    {
        if (is_string($value) && !$this->allowWhitespace) {
            $value = trim($value);
        }

        return pyncer_nullify($value);
    }

    /**
     * @inheritdoc
     */
    public function getError(): ?string
    {
        return null;
    }
}
