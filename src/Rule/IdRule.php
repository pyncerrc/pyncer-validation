<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\Mapper\Query\MapperQueryInterface;
use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

use function intval;
use function Pyncer\nullify as pyncer_nullify;
use function strval;

class IdRule implements RuleInterface
{
    private array $tests = [];

    public function __construct(
        private MapperInterface $mapper,
        private string $column = 'id',
        private ?MapperQueryInterface $mapperQuery = null,
        private bool $allowNull = false,
        private bool $allowEmpty = false,
    ) {}

    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid integer value specified.');
        }

        return $this->clean($value);
    }

    public function isValid(mixed $value): bool
    {
        if ($value === null || $value === 0) {
            return ($this->allowNull || $this->allowEmpty);
        }

        if ($value < 0) {
            return false;
        }

        if (array_key_exists($value, $this->tests)) {
            return $this->tests[$value];
        }

        $model = $this->mapper->selectByColumns(
            [
                $this->column => $value
            ],
            $this->mapperQuery
        );

        if ($model) {
            $this->tests[$value] = true;
        } else {
            $this->tests[$value] = false;
        }

        return $this->tests[$value];
    }

    public function clean(mixed $value): mixed
    {
        if ($value === null) {
            if ($this->allowNull) {
                return null;
            }

            if ($this->allowEmpty) {
                return 0;
            }
        } elseif (pyncer_nullify($value) === null) {
            if ($this->allowEmpty) {
                return 0;
            }

            if ($this->allowNull) {
                return null;
            }
        }

        if (!$this->isValid($value)) {
            if ($this->allowNull) {
                return null;
            }

            return 0;
        }

        return max(0, intval($value));
    }

    public function getError(): ?string
    {
        return 'invalid';
    }
}
