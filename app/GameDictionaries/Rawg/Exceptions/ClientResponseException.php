<?php
declare(strict_types=1);


namespace App\GameDictionaries\Rawg\Exceptions;

use Throwable;

class ClientResponseException extends \Exception
{
    protected $response;

    public function __construct($response, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response): void
    {
        $this->response = $response;
    }
}
