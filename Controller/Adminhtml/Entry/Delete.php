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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Megventure\Faq\Api\EntryRepositoryInterface;

/**
 * Deletes one entry.
 */
class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Megventure_Faq::entry_delete';

    /**
     * @param Context $context
     * @param EntryRepositoryInterface $entryRepository
     */
    public function __construct(
        Context $context,
        private readonly EntryRepositoryInterface $entryRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Delete the entry named in the request and go back to the grid.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirect = $this->resultRedirectFactory->create();
        $entityId = (int) $this->getRequest()->getParam('entity_id');

        if ($entityId === 0) {
            $this->messageManager->addErrorMessage((string) __('No FAQ entry was selected.'));

            return $redirect->setPath('*/*/');
        }

        try {
            $this->entryRepository->deleteById($entityId);
            $this->messageManager->addSuccessMessage((string) __('The FAQ entry has been deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                (string) __('The FAQ entry could not be deleted: %1', $e->getMessage())
            );
        }

        return $redirect->setPath('*/*/');
    }
}
