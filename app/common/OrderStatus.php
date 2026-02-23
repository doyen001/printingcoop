<?php

namespace App\Common;

class OrderStatus {
    const Incomplete = 1;
    const New = 2;
    const Processing = 3;
    const Shipped = 4;
    const Delivered = 5;
    const Cancelled = 6;
    const Failed = 7;
    const Complete = 8;
    const ReadyForPickup = 9;

    public const names = [
        self::Incomplete => 'Incomplete',
        self::New => 'New Order',
        self::Processing => 'Processing',
        self::Shipped => 'Shipped',
        self::Delivered => 'Delivered',
        self::Cancelled => 'Cancelled',
        self::Failed => 'Failed',
        self::Complete => 'Complete',
        self::ReadyForPickup => 'Ready for Pickup',
    ];
}
