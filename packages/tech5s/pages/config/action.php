<?php

return [
    'pages' => [
        [
            'label' => 'Chỉnh sửa',
            'url' => url('gp/edit-page'),
            'icon' => 'fa fa-times-circle-o',
            // 'class' => 'testclass',
            // "target_blank" => false, // Không bắt buộc
            // "attributes" => 'modal-target="#test"', // Không bắt buộc - nếu là modal thì có thể thêm view modal tại component
            'query' => [
                'id' => [
                    'fix' => false,
                    'value' => 'id',
                ],
            ],
            // 'show' => [ //không bắt buộc
            //     [
            //         'key' => 'status',
            //         'value' => [1],
            //     ],
            //     [
            //         'key' => 'point_type_id',
            //         'value' => [1],
            //     ],
            // ],
        ],
    ],
];
