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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Megventure\Faq\Api\Data\EntryInterface;
use Megventure\Faq\Api\EntryRepositoryInterface;
use Megventure\Faq\Model\Entry;
use Megventure\Faq\Model\EntryFactory;
use Megventure\Faq\Model\ResourceModel\Entry as EntryResource;

/**
 * Saves an entry.
 */
class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Megventure_Faq::entry_save';

    /**
     * @param Context $context
     * @param EntryRepositoryInterface $entryRepository
     * @param EntryFactory $entryFactory
     * @param EntryResource $entryResource
     */
    public function __construct(
        Context $context,
        private readonly EntryRepositoryInterface $entryRepository,
        private readonly EntryFactory $entryFactory,
        private readonly EntryResource $entryResource
    ) {
        parent::__construct($context);
    }

    /**
     * Save the posted entry for the store view it was edited in.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirect = $this->resultRedirectFactory->create();

        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $data = $request->getPostValue();

        if (!$data) {
            return $redirect->setPath('*/*/');
        }

        $entityId = isset($data['entity_id']) ? (int) $data['entity_id'] : 0;
        $storeId = isset($data['store_id']) ? (int) $data['store_id'] : 0;

        try {
            $entry = $this->loadOrCreate($entityId, $storeId);
            $this->applyData($entry, $data);
            $this->entryRepository->save($entry, $storeId);

            $this->messageManager->addSuccessMessage((string) __('The FAQ entry has been saved.'));
            $this->_getSession()->setFormData(null);

            if ($this->getRequest()->getParam('back')) {
                return $redirect->setPath('*/*/edit', ['entity_id' => $entry->getId(), 'store' => $storeId]);
            }

            return $redirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                (string) __('The FAQ entry could not be saved: %1', $e->getMessage())
            );
        }

        $this->_getSession()->setFormData($data);

        return $entityId
            ? $redirect->setPath('*/*/edit', ['entity_id' => $entityId, 'store' => $storeId])
            : $redirect->setPath('*/*/new');
    }

    /**
     * Load the entry being edited, or make a blank one.
     *
     * @param int $entityId
     * @param int $storeId
     * @return Entry
     * @throws NoSuchEntityException
     */
    private function loadOrCreate(int $entityId, int $storeId): Entry
    {
        if ($entityId === 0) {
            /** @var Entry $entry */
            $entry = $this->entryFactory->create();

            return $entry;
        }

        /** @var Entry $entry */
        $entry = $this->entryRepository->getById($entityId, $storeId);

        return $entry;
    }

    /**
     * Copy the posted values onto the entry, rejecting an empty question.
     *
     * @param Entry $entry
     * @param array $data
     * @return void
     * @throws LocalizedException
     */
    private function applyData(Entry $entry, array $data): void
    {
        $question = trim((string) ($data['question'] ?? ''));
        if ($question === '') {
            throw new LocalizedException(__('A FAQ entry needs a question.'));
        }

        $productId = (int) ($data['product_id'] ?? 0);

        $entry->setQuestion($question);
        $entry->setAnswer(trim((string) ($data['answer'] ?? '')));
        $entry->setProductId($productId);
        $entry->setIsActive(!empty($data['is_active']));

        // A new entry goes to the end of its own list rather than to the top,
        // so adding one never reshuffles what the merchant already ordered.
        if (!$entry->getId()) {
            $entry->setPosition($this->entryResource->nextPosition($productId));
        } elseif (isset($data['position'])) {
            $entry->setPosition((int) $data['position']);
        }
    }
}
