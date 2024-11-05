<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_layout']['fields']['pdfGenerationConfig'] = [
    'inputType' => 'select',
    'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true],
    'type' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

PaletteManipulator::create()
    ->addField('pdfGenerationConfig', 'expert_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout')
;
