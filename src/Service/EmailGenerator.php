<?php


namespace App\Service;


class EmailGenerator
{
    private $mailer;

    /**
     * EmailGenerator constructor.
     * @param $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($title,$subject,$distinataire)
    {
        $messages = (new \Swift_Message('test'))
            ->setFrom('testagence6@gmail')
            ->setTo($distinataire)
            ->setBody($subject,'text/html');


        return $this->mailer->send($messages);
    }
}