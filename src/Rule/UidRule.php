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
    public const string EMPTY = '00000000-0000-0000-0000-000000000000';

    /**
     * @param mixed $empty The value to use as an empty value.
     */
    public function __construct(
        mixed $empty = self::EMPTY,
    ) {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
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

        // Should an empty value be different from the default,
        // still don't allow all zeros.
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
