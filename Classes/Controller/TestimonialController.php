<?php

declare(strict_types=1);

namespace OliverThiele\OtTestimonials\Controller;

use OliverThiele\OtTestimonials\Domain\Model\Testimonial;
use OliverThiele\OtTestimonials\Domain\Repository\TestimonialRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class TestimonialController extends ActionController
{
    public function __construct(
        private readonly TestimonialRepository $testimonialRepository,
    ) {
    }

    public function listAction(): ResponseInterface
    {
        $storagePidsString = $this->settings['flexForm']['storagePids'] ?? '';

        if ($storagePidsString !== '') {
            $storagePids = array_map(intval(...), explode(',', (string)$storagePidsString));
            $testimonials = $this->testimonialRepository->findAllInStorage($storagePids);
        } else {
            $testimonials = $this->testimonialRepository->findAll();
        }

        $this->view->assignMultiple([
            'testimonials' => $testimonials,
            'structuredData' => $this->buildStructuredData($testimonials),
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }

    /**
     * @param QueryResultInterface<int, Testimonial> $testimonials
     */
    private function buildStructuredData(QueryResultInterface $testimonials): string
    {
        $items = [];
        $position = 1;

        foreach ($testimonials as $testimonial) {
            /** @var Testimonial $testimonial */
            $item = [
                '@type' => 'Review',
                'position' => $position++,
                'reviewBody' => strip_tags((string)$testimonial->getQuote()),
                'author' => [
                    '@type' => 'Person',
                    'name' => $testimonial->getAuthorName(),
                ],
                'itemReviewed' => [
                    '@type' => 'Organization',
                    'name' => $this->settings['itemReviewedName'] ?? '',
                ],
            ];

            if ($testimonial->getAuthorPosition() !== '') {
                $item['author']['jobTitle'] = $testimonial->getAuthorPosition();
            }

            if ($testimonial->getCompanyName() !== '') {
                $item['author']['worksFor'] = [
                    '@type' => 'Organization',
                    'name' => $testimonial->getCompanyName(),
                ];
            }

            $items[] = $item;
        }

        if ($items === []) {
            return '';
        }

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => $items,
        ];

        return '<script type="application/ld+json">'
            . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            . '</script>';
    }
}
