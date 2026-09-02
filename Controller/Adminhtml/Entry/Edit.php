<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Controller\Adminhtml\Entry;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Megventure\Faq\Api\EntryRepositoryInterface;

/**
 * The edit form for one entry.
 */
class Edit extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Megventure_Faq::entry_save';

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param EntryRepositoryInterface $entryRepository
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $pageFactory,
        private readonly EntryRepositoryInterface $entryRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Render the form for an existing entry, or redirect if it is gone.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $storeId = (int) $this->getRequest()->getParam('store', 0);

        try {
            $entry = $this->entryRepository->getById($entityId, $storeId);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage((string) __('This FAQ entry no longer exists.'));

            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        /** @var \Magento\Backend\Model\View\Result\Page $page */
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Megventure_Faq::entry');
        $page->getConfig()->getTitle()->prepend((string) ($entry->getQuestion() ?: __('Edit FAQ Entry')));

        return $page;
    }
}
