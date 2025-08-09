<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function filter_var;
use function is_scalar;
use function strval;
use function trim;

// TODO: Option to support 'name <name@example.com>' format.
class EmailRule extends AbstractRule
{
    public function __construct()
    {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
        );
    }

    /**
     * @inheritdoc
     */
    public function isValidValue(mixed $value): bool
    {
        if (is_scalar($value)) {
            return true;
        }

        if ($value instanceof Stringable) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function isValidConstraint(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }

        return false;
    }
}
