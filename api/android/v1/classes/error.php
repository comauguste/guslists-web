<?php
namespace Error;

class Error{

    const SUCCESS = 200;
    const USER_NOT_FOUND = 210;

    private $status;
    private $message;

    public function  __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getErrorCodeAndMessage()
    {
        return ["statusCode" => $this->status,
                "message" => $this->getErrorMessage($this->status) ];
    }


    private function getErrorMessage($statusCode = Error::SUCCESS)
    {

        switch ($statusCode)
        {
            case Error::USER_NOT_FOUND :
                $errorMessage = "User not found.";
                break;
            default :
                $errorMessage = "Success";
        }

        return $errorMessage;
    }



}