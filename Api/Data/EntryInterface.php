<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Api\Data;

/**
 * One question and its answer.
 *
 * A shared entry (product id 0) is shown on every product page; an entry
 * attached to a product is shown only there.
 */
interface EntryInterface
{
    public const ENTITY_ID = 'entity_id';
    public const PRODUCT_ID = 'product_id';
    public const POSITION = 'position';
    public const IS_ACTIVE = 'is_active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const QUESTION = 'question';
    public const ANSWER = 'answer';
    public const STORE_ID = 'store_id';

    /**
     * The entry's own id, null before it has ever been saved.
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set the entry id.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Product this entry belongs to, or 0 when it is shared.
     *
     * @return int
     */
    public function getProductId(): int;

    /**
     * Attach the entry to a product, or pass 0 to share it across all of them.
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId(int $productId);

    /**
     * Sort order within the entry's own list; lower comes first.
     *
     * @return int
     */
    public function getPosition(): int;

    /**
     * Set the sort order.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position);

    /**
     * Whether the entry is published to the storefront.
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Publish or unpublish the entry.
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive);

    /**
     * The question, in the store view the entry was loaded for.
     *
     * @return string
     */
    public function getQuestion(): string;

    /**
     * Set the question.
     *
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question);

    /**
     * The answer, in the store view the entry was loaded for.
     *
     * @return string
     */
    public function getAnswer(): string;

    /**
     * Set the answer.
     *
     * @param string $answer
     * @return $this
     */
    public function setAnswer(string $answer);

    /**
     * When the entry was first saved.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * When the entry was last saved.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;
}
