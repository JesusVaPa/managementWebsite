<?php
declare(strict_types=1);

namespace GivenCode\Abstracts;


use http\Exception\RuntimeException;

abstract class AbstractDTO implements IDTO
{
    use DTOTrait;

    /**
     * Constructor for {@see AbstractDTO}.
     */
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     * @throws RuntimeException If the primary key value is not set.
     */
    public function getPrimaryKeyValue(): int
    {
        if (empty($this->id)) {
            throw new RuntimeException("Primary key value is not set.");
        }
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getPrimaryKeyColumnName(): string
    {
        return self::$primaryKeyColumnName;
    }

    /**
     * @inheritDoc
     */
    abstract public function getDatabaseTableName(): string;

}