<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model\ResourceModel\Entry;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Megventure\Faq\Model\Entry as EntryModel;
use Megventure\Faq\Model\ResourceModel\Entry as EntryResource;

/**
 * A list of entries, with their text joined for one store view.
 */
class Collection extends AbstractCollection
{
    /**
     * Columns that exist on both joined tables and so need qualifying.
     */
    private const AMBIGUOUS_FIELDS = ['entity_id', 'store_id'];

    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Qualify a filter field that both joined tables also carry.
     *
     * Once the value table is joined twice, a bare `entity_id` in the WHERE
     * clause is ambiguous and MySQL refuses the query. The UI form and the grid
     * filters both pass bare column names, and they pass them before the join
     * is added, so the qualifying happens unconditionally rather than only when
     * the join is already in place.
     *
     * @param string|array $field
     * @param mixed $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (is_string($field) && in_array($field, self::AMBIGUOUS_FIELDS, true)) {
            $field = 'main_table.' . $field;
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(EntryModel::class, EntryResource::class);
    }

    /**
     * Join the question and answer for a store view, falling back to store 0.
     *
     * Two left joins rather than one: the store's own row and the default row.
     * COALESCE then picks the more specific of the two per column, which is
     * what makes a partly translated entry show its translated question and
     * still fall back for the answer.
     *
     * @param int $storeId
     * @return $this
     */
    public function addStoreText(int $storeId): self
    {
        if ($this->getFlag('store_text_joined')) {
            return $this;
        }

        $valueTable = $this->getTable(EntryResource::VALUE_TABLE);

        $this->getSelect()
            ->joinLeft(
                ['store_value' => $valueTable],
                'store_value.entity_id = main_table.entity_id AND store_value.store_id = '
                . (int) $storeId,
                []
            )
            ->joinLeft(
                ['default_value' => $valueTable],
                'default_value.entity_id = main_table.entity_id AND default_value.store_id = 0',
                [
                    'question' => new \Zend_Db_Expr('COALESCE(store_value.question, default_value.question)'),
                    'answer' => new \Zend_Db_Expr('COALESCE(store_value.answer, default_value.answer)'),
                ]
            );

        $this->setFlag('store_text_joined', true);

        return $this;
    }

    /**
     * Published entries only.
     *
     * @return $this
     */
    public function addActiveFilter(): self
    {
        $this->addFieldToFilter('is_active', ['eq' => 1]);

        return $this;
    }

    /**
     * Entries for one product, optionally including the shared ones.
     *
     * Product-specific entries sort above shared ones, because an answer
     * written about this item is more use than a general one.
     *
     * @param int $productId
     * @param bool $includeShared
     * @return $this
     */
    public function addProductFilter(int $productId, bool $includeShared = true): self
    {
        $ids = $includeShared ? array_unique([$productId, 0]) : [$productId];
        $this->addFieldToFilter('product_id', ['in' => $ids]);

        $this->getSelect()->order(
            [new \Zend_Db_Expr('main_table.product_id = 0 ASC'), 'main_table.position ASC', 'main_table.entity_id ASC']
        );

        return $this;
    }

    /**
     * Drop entries that have no question after the store fallback.
     *
     * An entry with no text in any store is a draft somebody started and never
     * finished; showing an empty accordion row would be worse than showing
     * nothing.
     *
     * @return $this
     */
    public function addHasTextFilter(): self
    {
        $this->getSelect()->where(
            'COALESCE(store_value.question, default_value.question) IS NOT NULL AND '
            . "COALESCE(store_value.question, default_value.question) != ''"
        );

        return $this;
    }
}
