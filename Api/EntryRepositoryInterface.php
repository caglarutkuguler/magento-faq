<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Megventure\Faq\Api\Data\EntryInterface;

/**
 * Reading and writing FAQ entries.
 */
interface EntryRepositoryInterface
{
    /**
     * Save an entry, storing its text against the given store view.
     *
     * @param EntryInterface $entry
     * @param int $storeId
     * @return EntryInterface
     * @throws CouldNotSaveException
     */
    public function save(EntryInterface $entry, int $storeId = 0): EntryInterface;

    /**
     * Load one entry with the text for a store view.
     *
     * @param int $entityId
     * @param int $storeId
     * @return EntryInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId, int $storeId = 0): EntryInterface;

    /**
     * Delete an entry along with the text of every store view.
     *
     * @param EntryInterface $entry
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(EntryInterface $entry): void;

    /**
     * Delete an entry by its id.
     *
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $entityId): void;
}
