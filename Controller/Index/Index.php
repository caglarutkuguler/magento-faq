<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Megventure\Faq\Model\Config;

/**
 * The standalone FAQ page.
 *
 * Reached at /faq through the rewrite installed on setup, so the address is
 * the one people expect rather than the module's own route.
 */
class Index implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     * @param ForwardFactory $forwardFactory
     * @param Config $config
     */
    public function __construct(
        private readonly PageFactory $pageFactory,
        private readonly ForwardFactory $forwardFactory,
        private readonly Config $config
    ) {
    }

    /**
     * Render the page, or fall through to 404 when it is switched off.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        if (!$this->config->isFaqPageEnabled()) {
            return $this->forwardFactory->create()->forward('noroute');
        }

        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set($this->config->getHeading());

        return $page;
    }
}
