<?php

namespace InspiredMinds\ContaoPdfGeneration\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;

/** 
 * @Callback(table="tl_layout", target="fields.pdfGenerationConfig.options")
 */
class LayoutPdfGenerationConfigOptionsListener
{
    private array $pdfGenerationConfigs;

    public function __construct(array $pdfGenerationConfigs)
    {
        $this->pdfGenerationConfigs = $pdfGenerationConfigs;
    }

    public function __invoke(): array
    {
        return array_keys($this->pdfGenerationConfigs);
    }
}
