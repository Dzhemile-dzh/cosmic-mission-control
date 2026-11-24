<?php

declare(strict_types=1);

namespace Cosmic\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Override;

/**
 * @implements IteratorAggregate<int, AbstractSpacecraft>
 */
final readonly class Fleet implements IteratorAggregate, Countable
{
    /**
     * @var list<AbstractSpacecraft>
     */
    private array $spacecraft;

    public function __construct(AbstractSpacecraft ...$spacecraft)
    {
        $this->spacecraft = array_values($spacecraft);
    }

    /**
     * @return ArrayIterator<int, AbstractSpacecraft>
     */
    #[Override]
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->spacecraft);
    }

    #[Override]
    public function count(): int
    {
        return count($this->spacecraft);
    }
}
