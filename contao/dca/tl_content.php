<?php

declare(strict_types=1);

/*
 * This file is part of the Contao PDF Generation extension.
 *
 * (c) INSPIRED MINDS
 */

use InspiredMinds\ContaoPdfGeneration\Controller\ContentElement\GeneratePdfController;

$GLOBALS['TL_DCA']['tl_content']['palettes'][GeneratePdfController::TYPE] = '{type_legend},type,headline,title;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
