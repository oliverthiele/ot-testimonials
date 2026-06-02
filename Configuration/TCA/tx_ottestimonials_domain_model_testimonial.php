<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Resource\FileType;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial',
        'label' => 'author_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:ot_testimonials/Resources/Public/Icons/OtTestimonialsPlugin.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,
                quote, author_name, author_position, company_name, company_logo,
                --div--;core.form.tabs:access, starttime, endtime',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_ottestimonials_domain_model_testimonial',
                'foreign_table_where' => 'AND {#tx_ottestimonials_domain_model_testimonial}.{#pid}=###CURRENT_PID### AND {#tx_ottestimonials_domain_model_testimonial}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => ['type' => 'passthrough'],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    ['label' => '', 'invertStateDisplay' => true],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'searchable' => false,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => ['upper' => mktime(0, 0, 0, 1, 1, 2038)],
                'searchable' => false,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'quote' => [
            'exclude' => false,
            'label' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial.quote',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 8,
                'enableRichtext' => true,
                'fieldControl' => [
                    'fullScreenRichtext' => ['disabled' => false],
                ],
                'searchable' => false,
            ],
        ],
        'author_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial.author_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'author_position' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial.author_position',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'company_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial.company_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'company_logo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ot_testimonials/Resources/Private/Language/locallang_db.xlf:tx_ottestimonials_domain_model_testimonial.company_logo',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
                'overrideChildTca' => [
                    'types' => [
                        '0' => ['showitem' => '--palette--;;imageoverlayPalette,--palette--;;filePalette'],
                        FileType::TEXT->value => ['showitem' => '--palette--;;imageoverlayPalette,--palette--;;filePalette'],
                        FileType::IMAGE->value => ['showitem' => '--palette--;;imageoverlayPalette,--palette--;;filePalette'],
                        FileType::AUDIO->value => ['showitem' => '--palette--;;audioOverlayPalette,--palette--;;filePalette'],
                        FileType::VIDEO->value => ['showitem' => '--palette--;;videoOverlayPalette,--palette--;;filePalette'],
                        FileType::APPLICATION->value => ['showitem' => '--palette--;;imageoverlayPalette,--palette--;;filePalette'],
                    ],
                ],
            ],
        ],
    ],
];
