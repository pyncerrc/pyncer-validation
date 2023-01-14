<?php
namespace Pyncer\Validation;

use Pyncer\Validation\Rule\RuleInterface;
use Traversable;

use function array_merge;
use function array_key_exists;
use function iterator_to_array;
use function Pyncer\Array\ensure_array as pyncer_ensure_array;

class DataValidator
{
    /**
     * @var array<string, array<\Pyncer\Validation\Rule\RuleInterface>>
     */
    protected array $rules = [];

    public function __construct()
    {}

    /**
     * @param string|iterable<string> $key
     * @param \Pyncer\Validation\Rule\RuleInterface ...$rules
     * @return static
     */
    public function addRules(
        string|iterable $key,
        RuleInterface ...$rules
    ): static
    {
        if ($key instanceof \Traversable) {
            $keys = iterator_to_array($key, true);
        } else {
            $keys = pyncer_ensure_array($key, ['']);
        }

        foreach ($keys as $key) {
            if (array_key_exists($key, $this->rules)) {
                $this->rules[$key] = array_merge($this->rules[$key], $rules);
            } else {
                $this->rules[$key] = $rules;
            }
        }

        return $this;
    }

    /**
     * @param string ...$keys
     * @return static
     */
    public function deleteRules(string ...$keys): static
    {
        foreach ($keys as $key) {
            unset($this->rules[$key]);
        }

        return $this;
    }

    /**
     * @param string ...$keys
     * @return bool
     */
    public function hasRules(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->rules)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return static
     */
    public function clearRules(): static
    {
        $this->rules = [];

        return $this;
    }

    /**
     * @param iterable<string, mixed> $data
     * @return iterable<string, mixed>
     */
    public function defend(iterable $data): iterable
    {
        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data, true);
        }

        foreach ($data as $key => $value) {
            if (!isset($this->rules[$key])) {
                continue;
            }

            foreach ($this->rules[$key] as $rule) {
                $value = $rule->defend($value);
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @param iterable<string, mixed> $data
     * @return bool
     */
    public function isValid(iterable $data): bool
    {
        if ($data instanceof Traversable) {
            $data = iterator_to_array($data, true);
        }

        foreach ($data as $key => $value) {
            if (!isset($this->rules[$key])) {
                continue;
            }

            foreach ($this->rules[$key] as $rule) {
                if (!$rule->isValid($value)) {
                    return false;
                }

                $value = $rule->clean($value);
            }
        }

        return true;
    }

    /**
     * @param iterable<string, mixed> $data
     * @return iterable<string, mixed>
     */
    public function clean(iterable $data): iterable
    {
        if ($data instanceof Traversable) {
            $data = iterator_to_array($data, true);
        }

        foreach ($data as $key => $value) {
            if (!isset($this->rules[$key])) {
                continue;
            }

            foreach ($this->rules[$key] as $rule) {
                $value = $rule->clean($value);
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @param iterable<string, mixed> $data
     * @return iterable<string, string>
     */
    public function getErrors(iterable $data): iterable
    {
        $errors = [];

        if ($data instanceof Traversable) {
            $data = iterator_to_array($data, true);
        }

        foreach ($data as $key => $value) {
            if (!isset($this->rules[$key])) {
                continue;
            }

            foreach ($this->rules[$key] as $rule) {
                if (!$rule->isValid($value)) {
                    $error = $rule->getError();
                    if ($error !== null) {
                        $errors[$key] = $error;
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}
