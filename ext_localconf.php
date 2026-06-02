<?php

declare(strict_types=1);

use OliverThiele\OtTestimonials\Controller\TestimonialController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(
    static function () {
        ExtensionUtility::configurePlugin(
            'OtTestimonials',
            'Testimonials',
            [
                TestimonialController::class => 'list',
            ],
            [
                TestimonialController::class => '',
            ]
        );
    }
);
