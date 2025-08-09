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
     * @param mixed $empty The value to use as an empty value.
     */
    public function __construct(
        protected array $values,
        bool $allowNull = false,
        bool $allowEmpty = false,
        mixed $empty = '',
    ) {
        if (in_array(null, $values, true) ||
            in_array('', $values, true)
        ) {
            throw new InvalidArgumentException(
                'Values cannot contain null or an empty value.'
            );
        }

        if (is_array($empty)) {
            foreach ($empty as $value) {
                if (in_array($value, $values, true)) {
                    throw new InvalidArgumentException(
                        'Values cannot contain null or an empty value.'
                    );
                }
            }
        } else {
            if (in_array($empty, $values, true)) {
                throw new InvalidArgumentException(
                    'Values cannot contain null or an empty value.'
                );
            }
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
