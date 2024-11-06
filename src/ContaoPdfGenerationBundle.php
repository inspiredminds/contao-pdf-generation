<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPdfGeneration;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPdfGenerationBundle extends Bundle
{
    public const TRIGGER_PARAM = 'generate_pdf';
    public const REQUEST_ATTRIBUTE = '_pdf_generation_config';

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
