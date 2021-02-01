<?php


namespace App\GameDictionaries\Search;


class CriteriaCollection
{
    /** @var CriteriaInterface[] */
    protected array $criteria = [];

    public function push(CriteriaInterface $criteria)
    {
        $this->criteria[] = $criteria;
    }

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
