<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Data\Mapper\MapperInterface;
use Pyncer\Data\MapperQuery\MapperQueryInterface;
use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function array_key_exists;
use function intval;
use function is_string;
use function trim;

class IdRule extends AbstractRule
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
     */
    public function __construct(
        private MapperInterface $mapper,
        private string $column = 'id',
        private ?MapperQueryInterface $mapperQuery = null,
    ) {
        parent::__construct(
            allowNull: true,
            allowEmpty: true,
            empty: 0,
        );
    }

    /**
     * @inheritdoc
     */
    protected function isValidValue(mixed $value): bool
    {
        if (!is_int($value)) {
            return false;
        }

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
}
