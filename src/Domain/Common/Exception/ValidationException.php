<?php

namespace App\Domain\Common\Exception;

use Throwable;

final class ValidationException extends \Exception
{

    /** @var array */
    protected $params;

    public function __construct($message = "", array $params = [])
    {
        parent::__construct($message);

        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
