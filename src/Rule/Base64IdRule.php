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
    public function __construct() {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
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
