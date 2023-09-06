<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function is_scalar;
use function Pyncer\String\len as pyncer_str_len;
use function Pyncer\String\sub as pyncer_str_sub;
use function strval;
use function trim;

class PasswordRule extends AbstractRule
{
    public function __construct(
        protected ?int $minLength = null,
        protected ?int $maxLength = null,
        protected bool $requireNumericCharacters = false,
        protected bool $requireAlphaCharacters = false,
        protected bool $requireLowerCaseCharacters = false,
        protected bool $requireUpperCaseCharacters = false,
        protected bool $requireSpecialCharacters = false,
        protected string $specialCharacters = '+=-_!@#$%^&*()?<>{}[]"\'.,`~|\\/:;',
        bool $allowWhitespace = false,
    ) {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
            allowWhitespace: $allowWhitespace,
        );
    }

    /**
     * @inheritdoc
     */
    protected function isValidValue(mixed $value): bool
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
        $value = strval($value);

        if (!$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->minLength !== null && pyncer_str_len($value) < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
            return false;
        }

        if ($this->requireNumericCharacters) {
            if (!preg_match('/\d+/', $value)) {
                return false;
            }
        }

        if ($this->requireAlphaCharacters) {
            if (!preg_match('/\p{L}+/u', $value)) {
                return false;
            }
        }

        if ($this->requireLowerCaseCharacters) {
            if (!preg_match('/\p{Ll}+/u', $value)) {
                return false;
            }
        }

        if ($this->requireUpperCaseCharacters) {
            if (!preg_match('/\p{Lu}+/u', $value)) {
                return false;
            }
        }

        if ($this->requireSpecialCharacters) {
            $pattern = '/[' . preg_quote($this->specialCharacters, '/') . ']+/';
            if (!preg_match($pattern, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = strval($value);

        if (!$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
            return pyncer_str_sub($value, 0, $this->maxLength);
        }

        return $value;
    }
}
