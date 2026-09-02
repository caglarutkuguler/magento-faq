<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Megventure\Faq\Api\Data\EntryInterface;
use Megventure\Faq\Api\EntryRepositoryInterface;
use Megventure\Faq\Model\ResourceModel\Entry as EntryResource;

/**
 * @inheritDoc
 */
class EntryRepository implements EntryRepositoryInterface
{
    /**
     * @param EntryResource $resource
     * @param EntryFactory $entryFactory
     */
    public function __construct(
        private readonly EntryResource $resource,
        private readonly EntryFactory $entryFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(EntryInterface $entry, int $storeId = 0): EntryInterface
    {
        /** @var Entry $entry */
        $entry->setData(EntryInterface::STORE_ID, $storeId);

        try {
            $this->resource->save($entry);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('The FAQ entry could not be saved: %1', $e->getMessage()), $e);
        }

        return $entry;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $entityId, int $storeId = 0): EntryInterface
    {
        /** @var Entry $entry */
        $entry = $this->entryFactory->create();
        $entry->setData(EntryInterface::STORE_ID, $storeId);
        $this->resource->load($entry, $entityId);

        if (!$entry->getId()) {
            throw new NoSuchEntityException(__('No FAQ entry with ID %1.', $entityId));
        }

        return $entry;
    }

    /**
     * @inheritDoc
     */
    public function delete(EntryInterface $entry): void
    {
        /** @var Entry $entry */
        try {
            $this->resource->delete($entry);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('The FAQ entry could not be removed: %1', $e->getMessage()), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $entityId): void
    {
        $this->delete($this->getById($entityId));
    }
}
