<?php
declare(strict_types=1);

namespace GivenCode\Abstracts;

interface IDTO
{

    /**
     * Returns the database primary key value of this DTO object.
     *
     * @return int
     *
     */
    public function getPrimaryKeyValue(): int;

    /**
     * Returns the database column name for the primary key of this DTO object.
     *
     * @return string
     *
     */
    public function getPrimaryKeyColumnName(): string;

    /**
     * Returns the database table name of this DTO object
     *
     * @return string
     */
    public function getDatabaseTableName(): string;

}