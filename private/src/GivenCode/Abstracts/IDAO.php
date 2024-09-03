<?php
declare(strict_types=1);

namespace GivenCode\Abstracts;

/**
 * Interface for DAO-type objects enforcing basic database operations methods.
 *
 */
interface IDAO
{

    /**
     * Retrieves a record of a certain DTO entity from the database and returns
     * an appropriate DTO object instance.
     *
     * @param int $id The identifier value of the record to obtain.
     * @return AbstractDTO|null The created object DTO instance or null if no record was found for the specified id.
     *
     */
    public function getById(int $id): ?AbstractDTO;

    /**
     * Creates a record for a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to create a record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     */
    public function create(AbstractDTO $dto): AbstractDTO;

    /**
     * Updates the record of a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to update the record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     */
    public function update(AbstractDTO $dto): AbstractDTO;

    /**
     * Deletes the record of a certain DTO entity in the database.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to delete the record of.
     * @return void
     *
     */
    public function delete(AbstractDTO $dto): void;

    /**
     * Deletes the record of a certain DTO entity in the database based on its identifier.
     *
     * @param int $id The identifier of the DTO entity to delete
     * @return void
     *
     */
    public function deleteById(int $id): void;

}