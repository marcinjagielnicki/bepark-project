<?php
declare(strict_types=1);


namespace App\GameDictionaries\Search;


class CriteriaCollection
{
    /** @var CriteriaInterface[] */
    protected array $criteria = [];

    public function push(CriteriaInterface $criteria)
    {
        $this->criteria[] = $criteria;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
