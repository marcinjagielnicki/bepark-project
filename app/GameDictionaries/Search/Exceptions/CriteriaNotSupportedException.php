<?php


namespace App\GameDictionaries\Search\Exceptions;


use App\GameDictionaries\Search\CriteriaInterface;
use Throwable;

class CriteriaNotSupportedException extends \Exception
{
    private CriteriaInterface $criteria;

    public function __construct(CriteriaInterface $criteria, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->criteria = $criteria;
    }

    public function getCriteria(): CriteriaInterface
    {
        return $this->criteria;
    }
}
