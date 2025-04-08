<?php

namespace backend\models\helpers;

use Yii;
use yii\base\Component;
use yii\symfonymailer\Message;

class EmailHelper extends Component
{
    /**
     * Mengirim email
     *
     * @param string $to Alamat email penerima
     * @param string $subject Subjek email
     * @param string $body Isi email
     * @param array $attachments Lampiran (opsional)
     * @return bool Status pengiriman email
     */
    public static function sendEmail($to, $subject, $body,)
    {
        $mailer = Yii::$app->mailer;
        $message = $mailer->compose()
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($body);

        $success = $message->send();
        return  $success;
    }
}
