<?php

return [
    [
        'id' => 1,
        'name' => 'Grand Indonesia Mall',
        'address' => 'Jl. M.H. Thamrin No. 1',
        'city' => 'Jakarta Pusat',
        'latitude' => -6.1952,
        'longitude' => 106.8208,
        'image' => '/web_parking_reservation/public/assets/images/mall-image/grand-indonesia-mall.jpg',
        'is_active' => true,

        'prices' => [
            'regular' => 15000,
            'valet' => 50000,
        ],

        'floors' => [
            [
                'id' => 1,
                'code' => 'LG',
                'sort_order' => 1,

                'slots' => [
                    [
                        'id' => 1,
                        'code' => 'LG-A01',
                        'parking_type' => 'REGULER',
                        'is_active' => true,
                    ],
                    [
                        'id' => 2,
                        'code' => 'LG-A02',
                        'parking_type' => 'REGULER',
                        'is_active' => true,
                    ],
                    [
                        'id' => 3,
                        'code' => 'LG-V01',
                        'parking_type' => 'VALET',
                        'is_active' => true,
                    ],
                ],
            ],

            [
                'id' => 2,
                'code' => 'GF',
                'sort_order' => 2,

                'slots' => [
                    [
                        'id' => 4,
                        'code' => 'GF-A01',
                        'parking_type' => 'REGULER',
                        'is_active' => true,
                    ],
                    [
                        'id' => 5,
                        'code' => 'GF-A02',
                        'parking_type' => 'REGULER',
                        'is_active' => true,
                    ],
                ],
            ],
        ],
    ],
];

?>