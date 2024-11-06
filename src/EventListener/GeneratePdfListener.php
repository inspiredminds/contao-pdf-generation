<?php

namespace InspiredMinds\ContaoPdfGeneration\EventListener;

use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class GeneratePdfListener
{
    private array $pdfGenerationConfigs;

    public function __construct(array $pdfGenerationConfigs)
    {
        $this->pdfGenerationConfigs = $pdfGenerationConfigs;
    }

    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_POST) || !$request->request->has(ContaoPdfGenerationBundle::TRIGGER_PARAM)) {
            return;
        }

        $configKey = $request->attributes->get(ContaoPdfGenerationBundle::REQUEST_ATTRIBUTE);

        if (!$configKey || !($config = ($this->pdfGenerationConfigs[$configKey] ?? null))) {
            return;
        }
    }
}
