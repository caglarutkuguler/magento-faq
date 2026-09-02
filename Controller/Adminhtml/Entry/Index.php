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
use Magento\Framework\View\Result\PageFactory;

/**
 * The entry list.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Megventure_Faq::entry';

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Render the grid page.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Page $page */
        $page = $this->pageFactory->create();
        $page->setActiveMenu('Megventure_Faq::entry');
        $page->getConfig()->getTitle()->prepend((string) __('Product FAQ'));

        return $page;
    }
}
