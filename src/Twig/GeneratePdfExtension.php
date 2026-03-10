<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration\Twig;

use Contao\CoreBundle\Twig\ContaoTwigUtil;
use Contao\CoreBundle\Twig\Loader\ContaoFilesystemLoader;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGeneration;
use Twig\Attribute\AsTwigFunction;

class GeneratePdfExtension
{
    public function __construct(
        private readonly ContaoPdfGeneration $contaoPdfGeneration,
        private readonly ContaoFilesystemLoader $contaoFilesystemLoader,
    ) {
    }

    #[AsTwigFunction('render_as_pdf')]
    public function renderAsPdf(): string|null
    {
        if (!$this->contaoPdfGeneration->shouldGenerate()) {
            return null;
        }

        if (!$template = $this->contaoPdfGeneration->getCurrentConfig()['page_template'] ?? null) {
            return null;
        }

        try {
            return $this->contaoFilesystemLoader->getFirst(ContaoTwigUtil::getIdentifier($template));
        } catch (\Throwable) {
            return null;
        }
    }

    #[AsTwigFunction('is_pdf')]
    public function isRenderedAsPdf(): bool
    {
        return $this->contaoPdfGeneration->shouldGenerate() && $this->contaoPdfGeneration->getCurrentConfig();
    }
}
