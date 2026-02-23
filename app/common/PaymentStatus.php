<?php

namespace App\Common;

class PaymentStatus {
    const Pending = 1;
    const Success = 2;
    const Failed = 3;

    public const names = [
        self::Pending => 'Pending',
        self::Success => 'Success',
        self::Failed => 'Failed',
    ];
}
