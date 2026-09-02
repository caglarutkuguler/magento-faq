<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model;

use Magento\Framework\Model\AbstractModel;
use Megventure\Faq\Api\Data\EntryInterface;
use Megventure\Faq\Model\ResourceModel\Entry as EntryResource;

/**
 * One FAQ entry.
 *
 * The question and answer are not columns on this row: they live per store
 * view in megventure_faq_value and are attached by the resource model when an
 * entry is loaded for a particular store.
 */
class Entry extends AbstractModel implements EntryInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(EntryResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int) $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $productId): self
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getPosition(): int
    {
        return (int) $this->getData(self::POSITION);
    }

    /**
     * @inheritDoc
     */
    public function setPosition(int $position): self
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $isActive): self
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getQuestion(): string
    {
        return trim((string) $this->getData(self::QUESTION));
    }

    /**
     * @inheritDoc
     */
    public function setQuestion(string $question): self
    {
        return $this->setData(self::QUESTION, $question);
    }

    /**
     * @inheritDoc
     */
    public function getAnswer(): string
    {
        return trim((string) $this->getData(self::ANSWER));
    }

    /**
     * @inheritDoc
     */
    public function setAnswer(string $answer): self
    {
        return $this->setData(self::ANSWER, $answer);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        $value = $this->getData(self::CREATED_AT);

        return $value === null ? null : (string) $value;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        $value = $this->getData(self::UPDATED_AT);

        return $value === null ? null : (string) $value;
    }

    /**
     * Whether this entry is shown on every product page.
     *
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->getProductId() === 0;
    }
}
