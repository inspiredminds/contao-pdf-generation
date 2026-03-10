<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGeneration;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE)]
class GeneratePdfController extends AbstractContentElementController
{
    public const TYPE = 'generate_pdf';

    public function __construct(private readonly ContaoPdfGeneration $contaoPdfGeneration)
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        if ($this->isBackendScope($request)) {
            return new Response();
        }

        if (!$this->contaoPdfGeneration->getCurrentConfig()) {
            return new Response();
        }

        $template->set('trigger_param', ContaoPdfGenerationBundle::TRIGGER_PARAM);

        return $template->getResponse();
    }
}
