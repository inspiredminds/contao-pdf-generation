<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration;

use Contao\CoreBundle\Routing\PageFinder;
use Contao\LayoutModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ContaoPdfGeneration
{
    public function __construct(
        private readonly PageFinder $pageFinder,
        private readonly RequestStack $requestStack,
        private readonly array $pdfGenerationConfigs,
    ) {
    }

    public function getCurrentConfig(): array|null
    {
        if (!$page = $this->pageFinder->getCurrentPage()) {
            return null;
        }

        if (!($layout = $page->getRelated('layout')) instanceof LayoutModel) {
            return null;
        }

        if (!$layout->pdfGenerationConfig) {
            return null;
        }

        return $this->pdfGenerationConfigs[$layout->pdfGenerationConfig] ?? null;
    }

    public function shouldGenerate(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request?->isMethod(Request::METHOD_POST) && $request?->request->has(ContaoPdfGenerationBundle::TRIGGER_PARAM);
    }
}
