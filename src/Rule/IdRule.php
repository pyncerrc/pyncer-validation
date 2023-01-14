<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Validation\Rule\IntRule;

use function array_key_exists;
use function intval;
use function is_string;
use function trim;

class IdRule extends IntRule
{
    /**
     * @var array<int, bool>
     */
    private array $tests = [];

    /**
     * @param \Pyncer\Data\Mapper\MapperInterface $mapper A mapper to query.
     * @param string $column The name of the database column that stores the id.
     * @param \Pyncer\Data\MapperQuery\MapperQueryInterface $mapperQuery
     *      Optional mapper query to limit query results.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     */
    public function __construct(
        private MapperInterface $mapper,
        private string $column = 'id',
        private ?MapperQueryInterface $mapperQuery = null,
        bool $allowNull = false,
        bool $allowEmpty = false,
    ) {
        parent::__construct(
            minValue: 0,
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(mixed $value): bool
    {
        if (!parent::isValid($value)) {
            return false;
        }

        if (!is_int($value) || $value === $this->empty) {
            return true;
        }

        return $this->isValidId($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function isValidId(mixed $value): bool
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        $value = intval($value);

        if ($value <= 0) {
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

    /**
     * {@inheritdoc}
     */
    public function clean(mixed $value): mixed
    {
        $value = parent::clean($value);

        if (!is_int($value) || $value === $this->empty) {
            return $value;
        }

        if (!$this->isValidId($value)) {
            if ($this->allowNull) {
                return null;
            }

            if ($this->allowEmpty) {
                return $this->empty;
            }

            return '';
        }

        return $value;
    }
}
