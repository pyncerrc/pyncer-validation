<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function is_scalar;
use function str_starts_with;
use function strval;
use function substr;
use function trim;

// TODO: Support milliseconds
class TimeRule extends AbstractRule
{
    public const string EMPTY = '00:00:00';

    /**
     * @param null|string $minValue The minimum value a time string can be.
     * @param null|string $maxValue The maximum value a time string can be.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param mixed $empty The value to use as an empty value.
     */
    public function __construct(
        private ?string $minValue = null,
        private ?string $maxValue = null,
        bool $allowNull = false,
        bool $allowEmpty = false,
        mixed $empty = self::EMPTY,
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            empty: $empty,
        );
    }

    protected function isValidValue(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        $pattern = '/^-?(8[0-3][0-9]|[0-7][0-9]{3}|[0-9]{1,2}):[0-5][0-9]:[0-5][0-9]$/';
        if (preg_match($pattern, $value)) {
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

        if ($this->minValue !== null &&
            $this->compareTimes($this->minValue, $value) < 0
        ) {
            return false;
        }

        if ($this->maxValue !== null &&
            $this->compareTimes($this->maxValue, $value) > 0
        ) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = parent::cleanConstraint($value);

        if (is_string($value)) {
            if ($this->minValue !== null &&
                $this->compareTimes($this->minValue, $value) < 0
            ) {
                return $this->minValue;
            }

            if ($this->maxValue !== null &&
                $this->compareTimes($this->maxValue, $value) > 0
            ) {
                return $this->maxValue;
            }
        }

        return $value;
    }

    private function compareTimes(string $a, string $b): int
    {
        if (str_starts_with($a, '-')) {
            if (str_starts_with($b, '-')) {
                return substr($a, 1) <=> substr($b, 1);
            }

            return -1;
        }

        if (str_starts_with($b, '-')) {
            return 1;
        }

        return $a <=> $b;
    }
}
