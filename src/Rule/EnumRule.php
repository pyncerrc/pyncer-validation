<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function in_array;
use function is_scalar;
use function strval;
use function trim;

// TODO: Support passing php enum::class for values.
class EnumRule extends AbstractRule
{
    /**
     * @param array<int|string, mixed> $values An array of allowed values.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param string $empty The value to use as an empty value.
     */
    public function __construct(
        private array $values,
        bool $allowNull = false,
        bool $allowEmpty = false,
        string $empty = '',
    ) {
        if (in_array(null, $values, true) ||
            in_array('', $values, true) ||
            in_array($empty, $values, true)
        ) {
            throw new InvalidArgumentException(
                'Values cannot contain null or an empty value.'
            );
        }

        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            empty: $empty,
        );
    }

    /**
     * @inheritdoc
     */
    public function isValidValue(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        return in_array($value, $this->values, true);
    }
}
