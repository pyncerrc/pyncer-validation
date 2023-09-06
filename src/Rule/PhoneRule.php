<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function filter_var;
use function is_scalar;
use function strval;
use function trim;

class PhoneRule extends AbstractRule
{
    public function __construct(
        protected bool $allowNanp = false,
        protected bool $allowE164 = false,
        protected bool $allowFormatting = false,
    ) {
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

        // If any odd characters then invalid
        if (!preg_match('/^\+?[\d\-\(\)\s]+$/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function isValidConstraint(mixed $value): bool
    {
        $value = trim(strval($value));

        if ($this->allowE164) {
            // Remove non digits and +.
            $e164Value = preg_replace('/[^\d\+]/', '', $value);

            if ($e164Value === null) {
                return false;
            }

            if (preg_match('/^\+?[1-9]\d{1,14}$/', $e164Value)) {
                return true;
            }
        }

        if ($this->allowNanp) {
            // Remove non digits and +.
            $nanpValue = preg_replace('/[^\d]/', '', $value);

            if ($nanpValue === null) {
                return false;
            }

            if (strlen($nanpValue) === 11 &&
                str_starts_with($nanpValue, '1')
            ) {
                $nanpValue = substr($nanpValue, 1);
            }

            if (preg_match('/^[0-9]{10}$/', $nanpValue)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = strval($value);

        /** @var string */
        $value = parent::cleanConstraint($value);

        if (!$this->allowFormatting) {
            $value = preg_replace('/[^\d\+]/', '', $value);

            if ($value === null) {
                return '';
            }
        }

        if ($this->allowE164) {
            return $value;
        }

        if ($this->allowNanp) {
            return ltrim($value, '+');
        }

        return '';
    }
}
