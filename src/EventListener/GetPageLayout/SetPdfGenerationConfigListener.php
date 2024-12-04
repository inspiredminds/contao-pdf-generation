<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\EventListener\GetPageLayout;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\LayoutModel;
use Contao\PageModel;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('getPageLayout')]
class SetPdfGenerationConfigListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private array $pdfGenerationConfigs,
    ) {
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
