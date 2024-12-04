<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback('tl_layout', 'fields.pdfGenerationConfig.options')]
class LayoutPdfGenerationConfigOptionsListener
{
    public function __construct(private readonly array $pdfGenerationConfigs)
    {
    }

    public function __invoke(): array
    {
        return array_keys($this->pdfGenerationConfigs);
    }
}
