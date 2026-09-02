<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model\ResourceModel\Entry\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Megventure\Faq\Model\ResourceModel\Entry\Collection as EntryCollection;
use Psr\Log\LoggerInterface;

/**
 * The collection behind the admin grid.
 *
 * It extends the ordinary entry collection so the grid shows the same joined
 * default-store text the storefront would use, rather than an entry list with
 * an empty question column.
 */
class Collection extends EntryCollection implements SearchResultInterface
{
    /**
     * @var AggregationInterface|null
     */
    private $aggregations = null;

    /**
     * @var SearchCriteriaInterface|null
     */
    private $searchCriteria = null;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $eventPrefix
     * @param string $eventObject
     * @param string $resourceModel
     * @param string|null $model
     * @param AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        EventManager $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        ?AdapterInterface $connection = null,
        ?\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * Join the default-store text once, before the grid runs its query.
     *
     * @return $this
     */
    protected function _beforeLoad(): self
    {
        $this->addStoreText(0);

        return parent::_beforeLoad();
    }

    /**
     * @inheritDoc
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @inheritDoc
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * @inheritDoc
     */
    public function setSearchCriteria(?\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        if ($searchCriteria instanceof SearchCriteriaInterface) {
            $this->searchCriteria = $searchCriteria;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @inheritDoc
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setItems(?array $items = null)
    {
        return $this;
    }
}
