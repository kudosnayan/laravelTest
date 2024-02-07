<?php

namespace App\Models\Traits\Methods;

use App\Mail\SendEmail;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;

/**
 * Trait EmailMethod.
 */
trait EmailMethod
{
    /**
     * Get all admin emails
     *
     * @param $key
     * @return value
     */
    public static function getEmails()
    {
        $emails = Email::$emails;
        foreach ($emails as $key => $email) {
            $emails[$key] = Email::getEmail($key);
        }

        return $emails;
    }

    /**
     * Get email
     *
     * @param $key
     * @return value
     */
    public static function getEmail($id)
    {
        if (! isset(Email::$emails[$id])) {
            return false;
        }

        $email = Email::$emails[$id];
        $email['title'] = $id;
        $fromDB = Email::where('title', $id)->first();
        if ($fromDB) {
            $email['content'] = $fromDB->content;
            $email['subject'] = $fromDB->subject;
            $email['fromDB'] = true;
        } else {
            $view = view('emails.'.$id);
            $contents = $view->render();
            $email['content'] = $contents;
            $email['fromDB'] = false;
        }

        return $email;
    }

    public static function getEmailContent($id, $placeHolders = [])
    {
        $email = self::getEmail($id);

        foreach ($placeHolders as $placeholder => $replaceWith) {
            $email['content'] = str_replace($placeholder, $replaceWith, $email['content']);
        }

        return $email;
    }

    /**
     * Send email
     *
     * @param $key
     * @return value
     */
    public static function sendEmail($id, $placeHolders, $to, $emailContent = '', $attachData = null, $attachName = '', $attachType = '', $bcc = [])
    {

        $emailData = self::getEmail($id);
        if (! empty($emailData['body'])) {
            $body = Email::getEmail($emailData['body']);
            $emailData['content'] = str_ireplace('[Main Content]', ($emailContent) ? $emailContent : $emailData['content'], $body['content']);
        }
        foreach ($placeHolders as $placeholder => $replaceWith) {
            $emailData['content'] = str_replace($placeholder, $replaceWith, $emailData['content']);
            $emailData['subject'] = str_replace($placeholder, $replaceWith, $emailData['subject']);
        }

        $mail = Mail::to($to);
        if ($bcc) {
            $mail->bcc($bcc);
        }

        return $mail->send(
            new SendEmail($emailData, base64_encode($attachData), $attachName, $attachType)
        );
    }
}
