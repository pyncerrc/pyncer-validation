<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function is_scalar;
use function preg_quote;
use function preg_replace;
use function Pyncer\String\len as pyncer_str_len;
use function Pyncer\String\sub as pyncer_str_sub;
use function Pyncer\String\to_lower as pyncer_str_to_lower;
use function Pyncer\String\to_upper as pyncer_str_to_upper;
use function str_contains;
use function strval;
use function trim;

class AliasRule extends AbstractRule
{
    public function __construct(
        protected bool $allowNumericCharacters = false,
        protected bool $allowLowerCaseCharacters = false,
        protected bool $allowUpperCaseCharacters = false,
        protected bool $allowUnicodeCharacters = false,
        protected string $separatorCharacters = '',
        protected string $replacementCharacter = '',
    ) {
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
        if (!is_scalar($value) && !$value instanceof Stringable) {
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

        if ($this->cleanConstraint($value) === '') {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = strval($value);

        /** @var string */
        $value = parent::cleanConstraint($value);

        $pattern = '';

        if ($this->allowUnicodeCharacters) {
            $pattern .= '\p{L}';

            if ($this->allowLowerCaseCharacters) {
                if (!$this->allowUpperCaseCharacters) {
                    $value = pyncer_str_to_lower($value);
                }
            } elseif ($this->allowUpperCaseCharacters) {
                $value = pyncer_str_to_upper($value);
            }
        } elseif ($this->allowLowerCaseCharacters) {
            $pattern .= 'a-z';

            if ($this->allowUpperCaseCharacters) {
                $pattern .= 'A-Z';
            } else {
                $value = pyncer_str_to_lower($value);
            }
        } elseif ($this->allowUpperCaseCharacters) {
            $pattern .= 'A-Z';
            $value = pyncer_str_to_upper($value);
        }

        if ($this->allowNumericCharacters) {
            $pattern .= '0-9';
        }

        if ($this->separatorCharacters !== '') {
            $pattern .= preg_quote($this->separatorCharacters, '/');

            if (!str_contains($this->separatorCharacters, ' ')) {
                $char = pyncer_str_sub($this->separatorCharacters, 0, 1);
                $value = str_replace(' ', $char, $value);
            }
        }

        if ($pattern === '') {
            return '';
        }

        $value = preg_replace_callback(
            '/[^' . $pattern . ']/u',
            function ($match) {
                return $this->replacementCharacter;
            },
            $value
        );

        if ($value === null || $value === '') {
            return '';
        }

        // If only separator and replacement characters, then fail
        $test = $this->separatorCharacters . $this->replacementCharacter;
        if ($test !== '' && trim($value, $test) === '') {
            return '';
        }

        if ($this->separatorCharacters !== '') {
            // Trim separators from start and end of alias
            $value = trim($value, $this->separatorCharacters);

            // Remove repeating separators within alias
            $newAlias = '';
            $prevCharIsSeparator = false;
            $len = pyncer_str_len($value);

            for ($i = 0; $i < $len; ++$i) {
                $char = pyncer_str_sub($value, $i, 1);

                // No repeating separators
                if (str_contains($this->separatorCharacters, $char)) {
                    if ($prevCharIsSeparator) {
                        continue;
                    }

                    $prevCharIsSeparator = true;
                } else {
                    $prevCharIsSeparator = false;
                }

                $newAlias .= $char;
            }

            $value = $newAlias;
        }

        return $value;
    }
}
