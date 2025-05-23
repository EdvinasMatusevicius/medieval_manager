<?php

declare(strict_types=1);

return [
    'apple' => [
        'name' => 'Apple',
        'type' => 'food',
        'weight' => 0.2, // in kg
        'properties' => [
            'nutrition' => 10,
            'freshness_duration' => 7 // days
        ]
    ],
    'wooden_mug' => [
        'name' => 'Wooden Mug',
        'type' => 'container',
        'weight' => 0.3,
        'properties' => [
            'capacity' => 0.5, // liters
            'durability' => 50
        ]
    ]
    // More items can be added here
]; 