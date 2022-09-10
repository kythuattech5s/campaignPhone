<?php

return [

    // Không sửa chỗ này ghi đè bên dưới nếu cần
    "default" => [
        [
            "key_catch" => "pivot_",
            "class" => "\CustomTable\Controllers\BaseController",
            "insert_method" => "__insertPivots",
            "update_method" => "__updatePivots",
        ],
        [
            "key_catch" => "rs_create_",
            "class" => "\CustomTable\Controllers\BaseController",
            "insert_method" => "__insertCreateDataMapTable",
            "update_method" => "__updateDataMapTable"
        ]
    ],

    //Custom cho bảng
];
