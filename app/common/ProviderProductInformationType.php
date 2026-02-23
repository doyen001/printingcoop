<?php

namespace App\Common;

class ProviderProductInformationType {
    const Normal = 0;
    const RollLabel = 1;
    const Decal = 2;

    public const names = [
        self::Normal => 'Normal',
        self::RollLabel => 'RollLabel',
        self::Decal => 'Decal',
    ];
}
