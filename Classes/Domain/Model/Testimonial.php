<?php

declare(strict_types=1);

namespace OliverThiele\OtTestimonials\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Testimonial extends AbstractEntity
{
    protected string $quote = '';

    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $authorName = '';

    protected string $authorPosition = '';

    protected string $companyName = '';

    #[Extbase\ORM\Cascade(['value' => 'remove'])]
    protected ?FileReference $companyLogo = null;

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): void
    {
        $this->quote = $quote;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    public function getAuthorPosition(): string
    {
        return $this->authorPosition;
    }

    public function setAuthorPosition(string $authorPosition): void
    {
        $this->authorPosition = $authorPosition;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function getCompanyLogo(): ?FileReference
    {
        return $this->companyLogo;
    }

    public function setCompanyLogo(?FileReference $companyLogo): void
    {
        $this->companyLogo = $companyLogo;
    }
}
