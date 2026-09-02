<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model\Entry;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Megventure\Faq\Model\Entry;
use Megventure\Faq\Model\ResourceModel\Entry\Collection;
use Megventure\Faq\Model\ResourceModel\Entry\CollectionFactory;

/**
 * Feeds the edit form.
 *
 * The text shown is the one for the store view being edited, with the default
 * store's text as the fallback, so opening a store view that has no
 * translation yet starts from the default rather than from an empty box.
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array<int, array<string, mixed>>|null
     */
    private ?array $loadedData = null;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $storeId = (int) $this->request->getParam('store', 0);
        $this->loadedData = [];

        /** @var Collection $collection */
        $collection = $this->collection;
        $collection->addStoreText($storeId);

        /** @var Entry $entry */
        foreach ($collection->getItems() as $entry) {
            $this->loadedData[$entry->getId()] = [
                'entity_id' => $entry->getId(),
                'product_id' => $entry->getProductId(),
                'position' => $entry->getPosition(),
                'is_active' => $entry->isActive() ? '1' : '0',
                'question' => $entry->getQuestion(),
                'answer' => $entry->getAnswer(),
                'store_id' => $storeId,
            ];
        }

        // A save that failed validation comes back here; showing the form empty
        // would throw away what the merchant typed.
        $persisted = $this->dataPersistor->get('megventure_faq_entry');
        if (!empty($persisted)) {
            $entityId = (int) ($persisted['entity_id'] ?? 0);
            $this->loadedData[$entityId] = $persisted;
            $this->dataPersistor->clear('megventure_faq_entry');
        }

        return $this->loadedData;
    }
}
