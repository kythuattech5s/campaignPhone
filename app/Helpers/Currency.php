<?php
namespace App\Helpers;
class Currency
{
    public static function format_money($number, $count, $roundUp = false)
    {
        if (strpos($number, '.') === false) {
            return [$number, 0];
        }
        $decimal = strlen(substr($number, strpos($number, '.') + 1));
        if ($decimal == 0) {
            return [$number, 0];
        }
        if ($decimal > $count) {
            $decimal = $count;
        } else {
            $count = $decimal;
        }
        if ($roundUp == true) {
            return [round($number, $decimal), $decimal];
        } else {
            return [bcadd(sprintf("%f", $number), '0', $decimal), $decimal];
        }
    }
    
    public static function getMoney($money, $locale = null)
    {
        if ($locale !== null) {
            if ($locale == 'vi') {
                return $money;
            }
        } else {
            if (\App::getLocale() == 'vi') {
                return $money;
            }
        }
        return $money / \SettingHelper::getSetting('exchange_rate');
    }

    public static function showMoney($money,bool $showInt = true, $locale = null)
    {
        $money = self::getMoney($money, $locale);
        $vnd = $showInt==true?' đ':'';
        $usd = $showInt==true?' USD':'';
        if ($money == 0) {
            return '0'.$vnd;
        }
        if ($locale == 'vi') {
            return number_format($money, 0, '.', ',').$vnd;
        }
        if ($locale == 'en') {
            return number_format($money, 2, '.', ',').$usd;
        }
        if (\App::getLocale() == 'vi') {
            return number_format($money, 0, '.', ',').$vnd;
        } else {
            $moneys = self::format_money($money, 2, true);
            return number_format($moneys[0], $moneys[1], '.', ',').$usd;
        }
    }
}
