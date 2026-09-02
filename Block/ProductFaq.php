<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Megventure\Faq\Model\Config;
use Megventure\Faq\Model\Entry;
use Megventure\Faq\Model\ResourceModel\Entry\CollectionFactory;

/**
 * The FAQ block on a product page.
 *
 * Everything it renders is in the server response. That is the point: a
 * crawler that does not run scripts still reads the answers, and so does an
 * assistant quoting the page.
 */
class ProductFaq extends AbstractProduct
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
     * Published entries for this product, shared ones included when allowed.
     *
     * @return Entry[]
     */
    public function getEntries(): array
    {
        if ($this->entries !== null) {
            return $this->entries;
        }

        $this->entries = [];

        $storeId = (int) $this->_storeManager->getStore()->getId();
        if (!$this->config->showOnProduct($storeId)) {
            return $this->entries;
        }

        $product = $this->getProduct();
        if (!$product instanceof Product || !$product->getId()) {
            return $this->entries;
        }

        $collection = $this->collectionFactory->create();
        $collection->addStoreText($this->config->fallbackToDefault($storeId) ? $storeId : $storeId)
            ->addActiveFilter()
            ->addProductFilter((int) $product->getId(), $this->config->showSharedOnProduct($storeId))
            ->addHasTextFilter();

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
     * On a product page the block needs its own heading; nothing else announces it.
     *
     * @return bool
     */
    public function showHeading(): bool
    {
        return true;
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
