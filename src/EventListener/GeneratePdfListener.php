<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration\EventListener;

use Contao\CoreBundle\Slug\Slug;
use InspiredMinds\ContaoPdfGeneration\ContaoPdfGenerationBundle;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

#[AsEventListener(priority: 1)]
class GeneratePdfListener
{
    public function __construct(
        private array $pdfGenerationConfigs,
        private readonly Slug $slugGenerator,
    ) {
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

        // Configure fonts
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        foreach ($config['fonts']['custom_fonts'] ?? [] as $fontName => $fontConfig) {
            foreach ($fontConfig as $variant => $path) {
                $pathInfo = pathinfo((string) $path);
                $fontDir = Path::normalize($pathInfo['dirname']);

                if (false === array_search($fontDir, $fontDirs, true)) {
                    $fontDirs[] = $fontDir;
                }

                $fontData[strtolower((string) $fontName)][strtoupper((string) $variant)] = $pathInfo['basename'];
            }
        }

        // Initialize PDF
        $pdf = new Mpdf([
            'fontDir' => $fontDirs,
            'fontdata' => $fontData,
            'format' => $config['custom_format'] ?? $config['format'] ?? null,
            'orientation' => $config['orientation'] ?? null,
            'margin_left' => $config['margins']['left'] ?? null,
            'margin_right' => $config['margins']['right'] ?? null,
            'margin_top' => $config['margins']['top'] ?? null,
            'margin_bottom' => $config['margins']['bottom'] ?? null,
            'margin_header' => $config['margins']['header'] ?? null,
            'margin_footer' => $config['margins']['footer'] ?? null,
        ]);

        // Set default font and size
        if ($defaultFont = ($config['fonts']['default_font'] ?? null)) {
            $pdf->SetDefaultFont($defaultFont);
        }

        if ($defaultSize = ($config['fonts']['default_size'] ?? null)) {
            $pdf->SetDefaultFontSize($defaultSize);
        }

        // Check to use template
        if ($pdfTemplate = ($config['pdf_template'] ?? null)) {
            $pdf->SetDocTemplate(Path::normalize($pdfTemplate), true);
        }

        // Initialize document and add a page
        $pdf->AddPage();

        // Write the HTML content
        $pdf->WriteHTML($event->getResponse()->getContent());

        // Reset template
        $pdf->SetDocTemplate();

        // Check to append PDF
        if ($pdfAppendix = ($config['pdf_appendix'] ?? null)) {
            $pdfAppendix = Path::normalize($pdfAppendix);

            if (file_exists($pdfAppendix)) {
                $pageCount = $pdf->SetSourceFile($pdfAppendix);

                for ($i = 1; $i <= $pageCount; ++$i) {
                    $pdf->AddPage();
                    $tpl = $pdf->ImportPage($i);
                    $pdf->UseTemplate($tpl);
                }
            }
        }

        $response = new Response($pdf->OutputBinaryData());

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $this->slugGenerator->generate($request->getHost().'/'.$request->getPathInfo()).'.pdf',
        );

        $response->headers->set('Content-Disposition', $disposition);

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
