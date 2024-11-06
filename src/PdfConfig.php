<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration;

class PdfConfig
{
    private ?string $format = null;
    private ?int $customFormatWidth = null;
    private ?int $customFormatHeight = null;
    private ?string $orientation = null;
    private ?string $defaultFont = null;
    private ?string $defaultFontSize = null;

    public function __construct(array $bundleConfig)
    {
    }

    public function getMpdfConfig(): array
    {
    }
}
