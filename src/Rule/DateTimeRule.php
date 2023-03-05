<?php
namespace Pyncer\Validation\Rule;

use DateTimeInterface;
use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function preg_match;
use function is_scalar;
use function strval;
use function trim;

// TODO: Support milliseconds.
class DateTimeRule extends AbstractRule
{
    /**
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param string $empty The value to use as an empty value.
     */
    public function __construct(
        bool $allowNull = false,
        bool $allowEmpty = false,
        string $empty = '0000-00-00 00:00:00',
    ) {
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
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        $value = trim(strval($value));

        $pattern = '/^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|11)(-)(0[1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468][048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(02)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02)(-)(29)))(\s)(([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]))$/';
        if (preg_match($pattern, $value)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function clean(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        return parent::clean($value);
    }
}
