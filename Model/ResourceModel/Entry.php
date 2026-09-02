<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Megventure\Faq\Api\Data\EntryInterface;

/**
 * Reads and writes an entry together with its per-store text.
 *
 * The text is kept in a second table so a store view can carry its own
 * translation. Store 0 holds the default text, and a store view without a row
 * of its own falls back to it - the same rule Magento uses everywhere else,
 * which is why an entry can never silently disappear from one language.
 */
class Entry extends AbstractDb
{
    public const VALUE_TABLE = 'megventure_faq_value';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('megventure_faq', 'entity_id');
    }

    /**
     * Attach the text for the store the model was loaded for.
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object): self
    {
        $storeId = (int) $object->getData(EntryInterface::STORE_ID);
        $row = $this->readValue((int) $object->getId(), $storeId);

        $object->setData(EntryInterface::QUESTION, $row['question'] ?? '');
        $object->setData(EntryInterface::ANSWER, $row['answer'] ?? '');

        return parent::_afterLoad($object);
    }

    /**
     * Write the text for the store the model was saved against.
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object): self
    {
        $storeId = (int) $object->getData(EntryInterface::STORE_ID);
        $connection = $this->getConnection();
        $table = $this->getTable(self::VALUE_TABLE);

        $connection->insertOnDuplicate(
            $table,
            [
                'entity_id' => (int) $object->getId(),
                'store_id' => $storeId,
                'question' => (string) $object->getData(EntryInterface::QUESTION),
                'answer' => (string) $object->getData(EntryInterface::ANSWER),
            ],
            ['question', 'answer']
        );

        return parent::_afterSave($object);
    }

    /**
     * The text for a store view, falling back to the default text.
     *
     * @param int $entityId
     * @param int $storeId
     * @return array<string, string>
     */
    public function readValue(int $entityId, int $storeId): array
    {
        if ($entityId === 0) {
            return [];
        }

        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable(self::VALUE_TABLE), ['store_id', 'question', 'answer'])
            ->where('entity_id = ?', $entityId)
            ->where('store_id IN (?)', array_unique([$storeId, 0]))
            // The store's own row first, the default second, so the first row
            // returned is always the most specific one available.
            ->order('store_id DESC')
            ->limit(1);

        $row = $connection->fetchRow($select);

        return is_array($row) ? $row : [];
    }

    /**
     * Every store view that has its own text for this entry.
     *
     * @param int $entityId
     * @return array<int, array<string, string>>
     */
    public function readAllValues(int $entityId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable(self::VALUE_TABLE), ['store_id', 'question', 'answer'])
            ->where('entity_id = ?', $entityId);

        $out = [];
        foreach ($connection->fetchAll($select) as $row) {
            $out[(int) $row['store_id']] = $row;
        }

        return $out;
    }

    /**
     * The next free position within a product's list.
     *
     * @param int $productId
     * @return int
     */
    public function nextPosition(int $productId): int
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['next' => 'MAX(position) + 1'])
            ->where('product_id = ?', $productId);

        return (int) $connection->fetchOne($select);
    }
}
