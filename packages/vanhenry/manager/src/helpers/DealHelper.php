<?php

namespace vanhenry\manager\helpers;

class DealHelper
{
    const TYPE_DEAL = 1;
    const TYPE_GIFT = 2;

    public static function getNameType(int $type)
    {
        switch ($type) {
            case static::TYPE_DEAL:
                return 'Mua Kèm Deal Sốc';
                break;

            case static::TYPE_GIFT:
                return 'Mua Để Nhận Quà';
                break;
        }
    }
}
