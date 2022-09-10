<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CronMailController extends Controller
{
    public function cronmail()
    {
        set_time_limit(0);
        // file_put_contents('cron_test.txt', 'alo');
        $hLock = fopen("cronmail.lock", "w+");
        if (!flock($hLock, LOCK_EX | LOCK_NB)) {
            die("Already running. Exiting...");
        }
        $i = 1;
        while (true) {
            $mails = \App\Models\QueueEmail::where(function ($q) {
                $q->where('status', 0)->orWhereNull('status');
            })->where(function ($q) {
                $q->where('is_sms', 0)->orWhereNull('is_sms');
            })->orderBy('id', 'asc')->take(1)->get();
            if ($mails->count() == 0) {
                break;
            }
            foreach ($mails as $k => $mail) {
                if ($this->checkFullCount(config('mail')['username'])) {
                    \SettingHelper::setConfigEmail();
                }
                $mail->status = 1;
                $mail->save();
                $attachments = json_decode($mail->attach_file, true);
                $attachments = is_array($attachments) ? $attachments : [];
                try {
                    app('MailHelper')->setEmail($mail->to)
                        ->setFromEmail()
                        ->setFrom($mail)
                        ->setSubject($mail->title)
                        ->setBcc(is_array($bcc = json_decode($mail->bcc)) ? $bcc : [])
                        ->setCc(is_array($cc = json_decode($mail->cc)) ? $cc : [])
                        ->setContent(function () use ($mail) {
                            return $mail->content;
                        })
                        ->send();
                    $result = 'Good Job!.';
                } catch (\Exception $error) {
                    return $error->getMessage();
                }
                $this->plusCountEmail(config('mail')['username']);
                $mail->status = 2;
                $mail->result = $result;
                $mail->save();
                sleep(2);
                $i++;
            }
        }
        flock($hLock, LOCK_UN);
        fclose($hLock);
        unlink('cronmail.lock');
    }

    function plusCountEmail($email)
    {
        \DB::table('emails')->where('email', $email)->increment('count_usage', 1);
    }

    public function checkFullCount($email)
    {
        $email = \DB::table('emails')->where('email', $email)->first();
        if ($email == null || $email->count_usage >= 100) {
            return true;
        }
        return false;
    }

    public function reset()
    {
        if (isset(request()->reset) && request()->reset = "yozcvuixafdshjl") {
            \DB::table('emails')->update([
                'count_usage' => 0,
            ]);
        }
    }
}
