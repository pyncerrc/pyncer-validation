<?php
namespace Pyncer\Validation;

use Pyncer\Validation\Rule\RuleInterface;
use Traversable

use function array_merge;
use function array_key_exists;
use function iterator_to_array;
use function Pyncer\Array\ensure_array as pyncer_ensure_array;

class DataValidator
{
    protected $rules;

    public function __construct()
    {
        $this->rules = [];
    }

    public function AddRules(string|iterable $key, RuleInterface ...$rules): static
    {
        if ($key instanceof \Traversable) {
            $key = iterator_to_array($key, true);
        } else {
            $keys = pyncer_ensure_array($key,  [null, '', false]);
        }

        foreach ($keys as $key) {
            if (!isset($this->rules[$key])) {
                $this->rules[$key] = $rules;
            } else {
                $this->rules[$key] = array_merge($this->rules[$key], $rules);
            }
        }

        return $this;
    }
    public function deleteRules(string ...$keys): static
    {
        foreach ($keys as $key) {
            unset($this->rules[$key]);
        }

        return $this;
    }
    public function hasRules(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $this->rules)) {
                return false;
            }
        }

        return true;
    }
    public function clearRules(): static
    {
        $this->rules = [];

        return $this;
    }

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
    public function getErrors(iterable $data): array
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
