<?php

namespace App\Helpers;

use Mail;

class MailHelper
{
    public $email = '';
    public $bcc = [];
    public $cc = [];
    public $subject = '';
    public $content = '';
    public $from_name = '';
    public $from_email = '';

    public function send()
    {
        $that = $this;
        try {
            Mail::html($this->content, function ($message) use ($that) {
                $message->from($that->from_email, $that->from_name)->subject($that->subject)->to($that->email);
                if (count($that->bcc) > 0) {
                    $message->bcc($that->bcc);
                }
                if (count($that->cc) > 0) {
                    $message->cc($that->cc);
                }
            });
            return ['code' => 200, 'message' => 'success'];
        } catch (\Exception $ex) {
            return ['code' => $ex->getCode(), 'message' => $ex->getMessage()];
        }
    }

    public function setEmail($email)
    {
        if (is_callable($email)) {
            $this->email = $email();
        } else {
            $this->email = $email;
        }
        return $this;
    }

    public function setSubject($subject)
    {
        if (is_callable($subject)) {
            $this->subject = $subject();
        } else {
            $this->subject = $subject;
        }
        return $this;
    }

    public function setBcc($bcc)
    {
        if (is_callable($bcc)) {
            $this->bcc = $bcc();
        } else {
            $this->bcc = $bcc;
        }
        return $this;
    }

    public function setCc($cc)
    {
        if (is_callable($cc)) {
            $this->cc = $cc();
        } else {
            $this->cc = $cc;
        }
        return $this;
    }

    public function setContent($content)
    {
        if (is_callable($content)) {
            $this->content = $content();
        } else {
            $this->content = $content;
        }
        return $this;
    }

    public function setFrom($obj)
    {
        $this->from_name = $obj->from ?? \SettingHelper::getSetting("mail_from_address", "");
        return $this;
    }

    public function setFromEmail()
    {
        $this->from_email = \SettingHelper::getSetting('mail_from_address') ?? 'CSKH.nhatquangshop@gmail.com';
        return $this;
    }
}
