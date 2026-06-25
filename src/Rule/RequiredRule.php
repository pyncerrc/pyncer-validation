<?php
namespace Pyncer\Validation\Rule;

use Closure;
use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;
use Stringable;

use function is_string;
use function Pyncer\nullify as pyncer_nullify;
use function trim;

class RequiredRule implements RuleInterface
{
    /** @var array<int, mixed> **/
    protected array $empty;

    /**
     * @param mixed $empty The value to use as an empty value.
     * @param bool $allowWhitespace When true, surrounding whitespace will
     *      be allowed.
     */
    public function __construct(
        mixed $empty = '',
        protected bool $allowWhitespace = false,
    ) {
        if (is_array($empty)) {
            $this->empty = $empty;
        } else {
            $this->empty = [$empty];
        }
    }

    /**
     * @inheritdoc
     */
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Empty value specified.');
        }

        return $this->clean($value);
    }

    /**
     * @inheritdoc
     */
    public function isValid(mixed $value): bool
    {
        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (is_string($value) && !$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->isEmptyValue($value)) {
            return false;
        }

        return (pyncer_nullify($value) !== null);
    }

    /**
     * @inheritdoc
     */
    public function clean(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function isValidAndClean(mixed $value): bool
    {
        if (!$this->isValid($value)) {
            return false;
        }

        if ($this->clean($value) !== $value) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError(): ?string
    {
        return 'required';
    }

    protected function isEmptyValue(mixed $value): bool
    {
        foreach ($this->empty as $emptyValue) {
            if ($emptyValue instanceof Closure) {
                if (call_user_func($emptyValue, $value)) {
                    return true;
                }
            } elseif ($emptyValue === $value) {
                return true;
            }
        }

        return false;
    }
}
