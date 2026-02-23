<?php

namespace App\Common;

class AttributeType {
    const Quantity = 0;
    const Shape = 1;
    const Size = 2;
    const Width = 3;
    const Length = 4;
    const Diameter = 5;
    const Depth = 6;
    const Pages = 7;
    const Sheets = 8;
    const Color = 9;
    const Stock = 10;
    const RectoVerso = 11;
    const Normal = 999;
    const Finishing = 9000;
    const YourText = 9099;
    const Turnaround = 9100;

    public const names = [
        self::Quantity => 'Quantity',
        self::Shape => 'Shape',
        self::Size => 'Size',
        self::Width => 'Width',
        self::Length => 'Length',
        self::Depth => 'Depth',
        self::Diameter => 'Diameter',
        self::Pages => 'Pages',
        self::Sheets => 'Sheets',
        self::Color => 'Color',
        self::Stock => 'Stock',
        self::RectoVerso => 'RectoVerso',
        self::Normal => 'Normal',
        self::Finishing => 'Finishing',
        self::YourText => 'YourText',
        self::Turnaround => 'Turnaround',
    ];

    public const matches = [
        self::Quantity => ['qty', 'quantity'],
        self::Shape => ['shape'],
        self::Size => ['size'],
        self::Width => ['width'],
        self::Length => ['length'],
        self::Depth => ['depth'],
        self::Diameter => ['diameter'],
        self::Pages => ['pages'],
        self::Sheets => ['sheets'],
        self::Color => ['color'],
        self::Stock => ['stock'],
        self::RectoVerso => ['rectoverso'],
        self::Normal => ['normal'],
        self::Finishing => ['finishing'],
        self::YourText => ['yourtext'],
        self::Turnaround => ['turnaround'],
    ];

    public static function type(string $name)
    {
		foreach (self::matches as $type => $match) {
            if (array_search(strtolower($name), $match, true) !== false)
                return $type;
        }
        return self::Normal;
    }
}
