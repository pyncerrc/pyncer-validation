<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function preg_match;
use function is_scalar;
use function strval;
use function trim;

class Base64IdRule extends AbstractRule
{
    /**
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     */
    public function __construct(
        bool $allowNull = false,
        bool $allowEmpty = false,
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
        );
    }

    /**
     * @inheritdoc
     */
    protected function isValidValue(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        $pattern = '/^[0-9a-zA-Z-_]+$/';
        if (preg_match($pattern, $value)) {
            return true;
        }

        return false;
    }
}
