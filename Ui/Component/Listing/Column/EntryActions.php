<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Edit and delete links on each grid row.
 */
class EntryActions extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Add an Edit and a Delete link to every row.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $name = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['entity_id'])) {
                continue;
            }

            $id = (int) $item['entity_id'];
            $item[$name] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl('megventure_faq/entry/edit', ['entity_id' => $id]),
                    'label' => __('Edit'),
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl('megventure_faq/entry/delete', ['entity_id' => $id]),
                    'label' => __('Delete'),
                    'post' => true,
                    'confirm' => [
                        'title' => __('Delete this FAQ entry?'),
                        'message' => __('It will be removed from every product page and from the FAQ page.'),
                    ],
                ],
            ];
        }

        return $dataSource;
    }
}
