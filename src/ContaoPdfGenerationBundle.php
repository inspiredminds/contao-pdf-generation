<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPdfGeneration;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPdfGenerationBundle extends Bundle
{
    public const TRIGGER_PARAM = '_generate_pdf';
    public const REQUEST_ATTRIBUTE = '_pdf_generation_config';

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
