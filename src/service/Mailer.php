<?php

namespace App\Src\Service;

use App\Config\Parameter;
use App\Src\Utils\Text;

class Mailer
{
    const CONTACT_MAIL = 'maxence.bonnet.host@gmail.com';
    const TEMPLATES_FOLDER = '../views/mail/';
    const CONTACT_FORM_TEMPLATE = "contactFormTemplate.html.php";
    const HEADERS_CONTENT_TYPE = "Content-type:text/html;charset=UTF-8" . "\r\n";
    const HEADERS_MIME_VERSION = "MIME-Version: 1.0" . "\r\n";

    /**
     * @var string
     */
    private $headers;
    /**
     * @var string
     */
    private $to;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $userEmail;

    /**
     * from $data => $firstname / $lastname / $mail / $message
     */
    public function sendContactForm(Parameter $data) : bool
    {
        $firstname = $data->get('firstname');
        $lastname = $data->get('lastname');

        $this->setTo(self::CONTACT_MAIL);
        $this->setSubject('Blog Contact Form : ' . $firstname . ' ' . $lastname);
        $this->setHeaders(self::HEADERS_CONTENT_TYPE);
        $this->setuserEmail($data->get('email'));
        $this->setMessage(Text::formatMailMessage($data->get('message')));
        
        ob_start();
        require self::TEMPLATES_FOLDER . self::CONTACT_FORM_TEMPLATE;
        $this->setMessage(ob_get_clean());

        return $this->sendMail($this->to,$this->subject,$this->message,$this->headers);
    }

    private function sendMail(string $to,string $subject,string $message,string $headers = "") : bool
    {
        return mail($to,$subject,$message,$headers); // Codacy doesn't like mail()
    }

    /**
     * @return string
     */
    public function getHeaders(){return $this->headers;}

    /**
     * @return string
     */
    public function getTo(){return $this->to;}

    /**
     * @return string
     */
    public function getSubject(){return $this->subject;}

    /**
     * @return string
     */
    public function getMessage(){return $this->message;}

    /**
     * @return string
     */
    public function getUserEmail(){return $this->userEmail;}

    
    /**
     * @param string $headers
     */
    public function setHeaders($headers){$this->headers = $headers;}

    /**
     * @param string $to
     */
    public function setTo($to){$this->to = $to;}

    /**
     * @param string $subject
     */
    public function setSubject($subject){$this->subject = $subject;}

    /**
     * @param string $message
     */
    public function setMessage($message){$this->message = $message;}

    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail){$this->userEmail = $userEmail;}

}