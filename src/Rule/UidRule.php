<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function preg_match;
use function is_scalar;
use function strval;
use function trim;

class UidRule extends AbstractRule
{
    /**
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param string $empty The value to use as an empty value.
     */
    public function __construct(
        bool $allowNull = false,
        bool $allowEmpty = false,
        string $empty = '00000000-0000-0000-0000-000000000000',
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            empty: $empty,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isValidValue(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        if ($value === '00000000-0000-0000-0000-000000000000') {
            return false;
        }

        $pattern = '/^[0-9a-fA-F]{8}-([0-9a-fA-F]{4}-){3}[0-9a-fA-F]{12}$/';
        if (preg_match($pattern, $value)) {
            return true;
        }

        return false;
    }

    public function clean(mixed $value): mixed
    {
        $value = parent::clean($value);

        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }
}
