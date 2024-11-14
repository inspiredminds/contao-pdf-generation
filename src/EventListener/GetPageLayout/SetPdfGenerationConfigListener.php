<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration\EventListener\GetPageLayout;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\LayoutModel;
use Contao\PageModel;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\Request;
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
        if (!$layout->pdfGenerationConfig) {
            return $layout;
        }

        if (!$config = ($this->pdfGenerationConfigs[$layout->pdfGenerationConfig] ?? null)) {
            return $layout;
        }

        if (!$request = $this->requestStack->getCurrentRequest()) {
            return $layout;
        }

        $request->attributes->set(ContaoPdfGenerationBundle::REQUEST_ATTRIBUTE, $layout->pdfGenerationConfig);

        if ($request->isMethod(Request::METHOD_POST) && $request->request->has(ContaoPdfGenerationBundle::TRIGGER_PARAM)) {
            $layout->template = $config['page_template'] ?? $layout->template;
        }

        return $layout;
    }
}
