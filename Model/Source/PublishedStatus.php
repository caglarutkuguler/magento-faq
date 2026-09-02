<?php
/**
 * Copyright 2007-2026 MEG Venture & Consulting Ltd.
 *
 * @author  MEG Venture & Consulting Ltd. <info@megventure.com>
 * @license MIT
 */
declare(strict_types=1);

namespace Megventure\Faq\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * The two states of an entry, worded for a grid column rather than a switch.
 *
 * Magento's shared Enable/Disable source reads as a command in a column of
 * states, so the grid says what an entry is instead.
 */
class PublishedStatus implements OptionSourceInterface
{
    /**
     * Options for the grid column and its filter.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 1, 'label' => __('Published')],
            ['value' => 0, 'label' => __('Not published')],
        ];
    }
}
