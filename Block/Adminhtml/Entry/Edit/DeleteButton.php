<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Block\Adminhtml\Entry\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * The Delete button, shown only when there is something to delete.
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @param Context $context
     */
    public function __construct(
        private readonly Context $context
    ) {
    }

    /**
     * Button definition, empty for an entry that does not exist yet.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $entityId = (int) $this->context->getRequest()->getParam('entity_id');

        if ($entityId === 0) {
            return [];
        }

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\''
                . __('Delete this FAQ entry? It will be removed from every product page and from the FAQ page.')
                . '\', \''
                . $this->context->getUrlBuilder()->getUrl('*/*/delete', ['entity_id' => $entityId])
                . '\')',
            'sort_order' => 20,
        ];
    }
}
