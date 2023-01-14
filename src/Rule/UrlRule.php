<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function filter_var;
use function is_scalar;
use function strval;
use function trim;

class UrlRule extends AbstractRule
{
    public function __construct()
    {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
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

        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        return false;
    }
}
