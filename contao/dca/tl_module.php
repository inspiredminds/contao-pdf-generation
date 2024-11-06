<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

use InspiredMinds\ContaoPdfGeneration\Controller\FrontendModule\GeneratePdfController;

$GLOBALS['TL_DCA']['tl_module']['palettes'][GeneratePdfController::TYPE] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
