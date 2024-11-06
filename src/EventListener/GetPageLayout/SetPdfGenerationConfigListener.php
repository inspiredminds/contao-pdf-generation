<?php

namespace InspiredMinds\ContaoPdfGeneration\EventListener\GetPageLayout;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\LayoutModel;
use Contao\PageModel;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\RequestStack;

/** 
 * @Hook("getPageLayout")
 */
class SetPdfGenerationConfigListener
{
    private RequestStack $requestStack;
    private array $pdfGenerationConfigs;

    public function __construct(RequestStack $requestStack, array $pdfGenerationConfigs)
    {
        $this->requestStack = $requestStack;
        $this->pdfGenerationConfigs = $pdfGenerationConfigs;
    }

    public function __invoke(PageModel $page, LayoutModel $layout): LayoutModel
    {
        if ($layout->pdfGenerationConfig && isset($this->pdfGenerationConfigs[$layout->pdfGenerationConfig])) {
            if ($request = $this->requestStack->getCurrentRequest()) {
                $request->attributes->set(ContaoPdfGenerationBundle::REQUEST_ATTRIBUTE, $layout->pdfGenerationConfig);
            }
        }

        return $layout;
    }
}
