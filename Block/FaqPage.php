<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Megventure\Faq\Model\Config;
use Megventure\Faq\Model\Entry;
use Megventure\Faq\Model\ResourceModel\Entry\CollectionFactory;

/**
 * Every published entry, on one page.
 */
class FaqPage extends Template
{
    /**
     * @var Entry[]|null
     */
    private ?array $entries = null;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Published entries, shared ones first and then the product-specific ones.
     *
     * @return Entry[]
     */
    public function getEntries(): array
    {
        if ($this->entries !== null) {
            return $this->entries;
        }

        $storeId = (int) $this->_storeManager->getStore()->getId();

        $collection = $this->collectionFactory->create();
        $collection->addStoreText($storeId)
            ->addActiveFilter()
            ->addHasTextFilter();
        $collection->getSelect()->order(
            [
                new \Zend_Db_Expr('main_table.product_id != 0 ASC'),
                'main_table.position ASC',
                'main_table.entity_id ASC',
            ]
        );

        /** @var Entry[] $items */
        $items = array_values($collection->getItems());
        $this->entries = $items;

        return $this->entries;
    }

    /**
     * Whether there is anything to render at all.
     *
     * @return bool
     */
    public function hasEntries(): bool
    {
        return $this->getEntries() !== [];
    }

    /**
     * The heading shown above the entries.
     *
     * @return string
     */
    public function getHeading(): string
    {
        return $this->config->getHeading((int) $this->_storeManager->getStore()->getId());
    }

    /**
     * Whether the first entry starts expanded.
     *
     * @return bool
     */
    public function openFirst(): bool
    {
        return $this->config->openFirst((int) $this->_storeManager->getStore()->getId());
    }
}
