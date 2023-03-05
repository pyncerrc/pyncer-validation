<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;
use StdClass;

use function boolval;
use function is_string;
use function trim;

class BoolRule implements RuleInterface
{
    /**
     * @param bool $allowNull When true, null vlaues are valid.
     */
    public function __construct(
        private bool $allowNull = false
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
        if ($this->allowNull && $value === null) {
            return null;
        }

        if ($value instanceof StdClass) {
            if ((array)$value) {
                return true;
            }

            return false;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return boolval($value);
    }

    /**
     * @inheritdoc
     */
    public function getError(): ?string
    {
        return 'invalid';
    }
}
