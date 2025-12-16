<?php
namespace App\Http\Exception;

use Exception;
use Illuminate\Support\MessageBag;

class InputErrorException extends \Exception
{
    /**
     * @param string     $message
     * @param int        $code
     * @param Exception  $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
