<?php

namespace App\Src\Utils;

class AlertMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $alertMessage;

    public function __construct(string $type,string $message)
    {
        $this->setMessage($message);
        $this->setType($type);
        $this->buildAlert();
    }

    private function buildAlert()
    {
        $this->setAlertMessage('<div class="alert alert-' . $this->getType() . ' alert-dismissible fade show" role="alert">' . $this->getMessage() . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
    }

    
    /**
     * @return string
     */
    public function getMessage(){return $this->message;}

    /**
     * @return string
     */
    public function getType(){return $this->type;}

    /**
     * @return string
     */
    public function getAlertMessage(){return $this->alertMessage;}    


    /**
     * @param string $message
     */
    public function setMessage($message){$this->message = $message;}

    /**
     * @param string $type
     */
    public function setType($type){$this->type = $type;}

    /**
     * @param string $alertMessage
     */
    public function setAlertMessage($alertMessage){$this->alertMessage = $alertMessage;}
}