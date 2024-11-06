<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Csrf\ContaoCsrfTokenManager;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\LayoutModel;
use Contao\ModuleModel;
use Contao\Template;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(type=self::TYPE, category="miscellaneous", template="mod_generate_pdf")
 */
class GeneratePdfController extends AbstractFrontendModuleController
{
    public const TYPE = 'generate_pdf';

    private ContaoCsrfTokenManager $contaoCsrfTokenManager;

    private array $pdfGenerationConfigs;

    public function __construct(ContaoCsrfTokenManager $contaoCsrfTokenManager, array $pdfGenerationConfigs)
    {
        $this->contaoCsrfTokenManager = $contaoCsrfTokenManager;
        $this->pdfGenerationConfigs = $pdfGenerationConfigs;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        if (($page = $this->getPageModel()) && ($layout = $page->getRelated('layout')) instanceof LayoutModel) {
            if (!$layout->pdfGenerationConfig || !isset($this->pdfGenerationConfigs[$layout->pdfGenerationConfig])) {
                return new Response();
            }
        }

        $template->requestToken = $this->contaoCsrfTokenManager->getDefaultTokenValue();
        $template->triggerParam = ContaoPdfGenerationBundle::TRIGGER_PARAM;

        return $template->getResponse();
    }
}
