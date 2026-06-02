<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

$pluginSignature = ExtensionUtility::registerPlugin(
    'OtTestimonials',
    'Testimonials',
    'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:ottestimonials_testimonials.wizard.name',
    'ot-testimonials-plugin',
    'plugins',
    'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:ottestimonials_testimonials.wizard.description',
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:flexform.tab.configuration, pi_flexform,',
    $pluginSignature,
    'after:subheader',
);

// TYPO3 v14: FlexForm registration via columnsOverrides (addPiFlexFormValue is deprecated)
$GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['columnsOverrides']['pi_flexform']['config']['ds']
    = 'FILE:EXT:ot_testimonials/Configuration/FlexForms/flexform_testimonials.xml';
