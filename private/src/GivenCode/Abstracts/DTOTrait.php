<?php
declare(strict_types=1);

namespace GivenCode\Abstracts;

use GivenCode\Exceptions\ValidationException;


trait DTOTrait
{
    protected static string $primaryKeyColumnName = "id";
    public int $id;

    /**
     * Getter for <code>Id</code>
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Setter for <code>Id</code>
     *
     * @param int $id
     * @throws ValidationException If the id is lower than 1.
     */
    public function setId(int $id): void
    {
        if ($id < 1) {
            throw new ValidationException("Id value cannot be inferior to 1.");
        }
        $this->id = $id;
    }

}