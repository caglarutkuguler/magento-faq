<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Typed reader for every setting this module owns.
 */
class Config
{
    private const XML_ENABLED = 'megventure_faq/general/enabled';
    private const XML_ON_PRODUCT = 'megventure_faq/display/on_product';
    private const XML_SHARED_ON_PRODUCT = 'megventure_faq/display/shared_on_product';
    private const XML_FAQ_PAGE = 'megventure_faq/display/faq_page';
    private const XML_OPEN_FIRST = 'megventure_faq/display/open_first';
    private const XML_FALLBACK = 'megventure_faq/display/fallback_to_default';
    private const XML_HEADING = 'megventure_faq/display/heading';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Master switch.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled(?int $storeId = null): bool
    {
        return $this->flag(self::XML_ENABLED, $storeId);
    }

    /**
     * Whether the block appears on product pages.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function showOnProduct(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId) && $this->flag(self::XML_ON_PRODUCT, $storeId);
    }

    /**
     * Whether shared entries join the product-specific ones.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function showSharedOnProduct(?int $storeId = null): bool
    {
        return $this->flag(self::XML_SHARED_ON_PRODUCT, $storeId);
    }

    /**
     * Whether the standalone FAQ page is published.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isFaqPageEnabled(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId) && $this->flag(self::XML_FAQ_PAGE, $storeId);
    }

    /**
     * Whether the first entry starts expanded.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function openFirst(?int $storeId = null): bool
    {
        return $this->flag(self::XML_OPEN_FIRST, $storeId);
    }

    /**
     * Whether a store view without its own text falls back to the default.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function fallbackToDefault(?int $storeId = null): bool
    {
        return $this->flag(self::XML_FALLBACK, $storeId);
    }

    /**
     * Heading shown above the entries.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getHeading(?int $storeId = null): string
    {
        $value = trim((string) $this->scopeConfig->getValue(
            self::XML_HEADING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));

        return $value !== '' ? $value : (string) __('Frequently asked questions');
    }

    /**
     * Read a yes/no value.
     *
     * @param string $path
     * @param int|null $storeId
     * @return bool
     */
    private function flag(string $path, ?int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
