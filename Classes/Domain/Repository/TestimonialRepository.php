<?php

declare(strict_types=1);

namespace OliverThiele\OtTestimonials\Domain\Repository;

use OliverThiele\OtTestimonials\Domain\Model\Testimonial;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<Testimonial>
 */
class TestimonialRepository extends Repository
{
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];

    /**
     * @return QueryResultInterface<int, Testimonial>
     */
    #[\Override]
    public function findAll(): QueryResultInterface
    {
        /** @phpstan-var QueryResultInterface<int, Testimonial> $result */
        $result = parent::findAll();
        return $result;
    }

    /**
     * @param array<int> $storagePids
     * @return QueryResultInterface<int, Testimonial>
     */
    public function findAllInStorage(array $storagePids): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($storagePids);
        return $query->execute();
    }
}
