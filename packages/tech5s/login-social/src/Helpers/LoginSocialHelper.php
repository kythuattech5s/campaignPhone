<?php

namespace Tech5s\LoginSocial\Helpers;

class LoginSocialHelper
{
    public static function redirectTo($url, $code, $message)
    {
        return redirect()->to($url)->with('messageNotify', $message)->with('typeNotify', $code);
    }

    public static function URLPrevious(bool $isSet = true, string $defaultUrl = '/')
    {
        $key = 'PREVIOUS_URL_RS';
        if ($isSet) {
            $url = url()->previous();
            $url = strpos($url, url('/')) >= 0 ? $url : url($defaultUrl);
            session()->put($key, $url);
        }

        if (!$isSet) {
            $url = is_null(($url = session()->get($key))) ? url()->previous() : $url;
            $url = $url == url()->current() ? url($defaultUrl) : $url;
            session()->forget($key, null);
        }

        $arrayNotRedirect = ['.js', '.css', '_debugbar', 'javascript', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', "accounts.google.com"];
        foreach ($arrayNotRedirect as $error) {
            if (strpos($url, $error) !== false) {
                $url = url($defaultUrl);
                break;
            }
        }
        return $url;
    }
}
