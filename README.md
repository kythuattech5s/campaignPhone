# Cấu hình bảng v_detail_tables

# SELECT

default_data
```
{
	"data": {
		"table": "h_group_users",
		"select": "id,name",
		"field_base": "id",
		"field_root": "parent",
		"relationship":"groupUser", 
		"field_show":"name",
		"first_value": 0,
		"where": [{
			"act": "1"
		}],
		"default": {}
	},
	"config": {
		"source": "normal",
		"has_search": "1",
		"multiselect": "1",
		"ajax": "0"
	}
}
```

relationship => phương thức trong relationship trong model
field_show => trường hiển thị

relationship
```
{
    "data": [
        {
            "name": "groupUser",
            "select": "id,name",
            "table":"h_group_users",
            "data":[]
        }
    ]
}
```

name => phương thức trong relationship trong model
select => trường cần lấy
table => bảng relationship

# PIVOT

default_data
```
{
    "pivot_table":"menu_menu_category",
    "origin_field":"menu_category_id",
    "target_table":"menus",
    "target_field":"menu_id",
    "target_select":["id","name","parent"],
    "relationship": "menus",
    "field_show":"name",
    "isAjaxSearch": "1",
    "recursive": "childs"
}


```
relationship => phương thức trong relationship trong model
field_show => trường hiển thị
isAjaxSearch => Tìm kiếm tránh trường hợp quá nhiều dữ liệu


relationship
```
{
    "data": [
        {
            "name": "menus",
            "select": "id,name",
            "table":"menus",
            "data":[]
        }
    ]
}

```

name => phương thức trong relationship trong model
select => trường cần lấy
table => bảng relationship


type_show

Không thêm "::" sẽ lấy component trong packages/vanhenry/views/folder với type tương ứng

# Tạo bảng mới trong quản trị

Thêm model đúng của bảng cần thêm
VD: \App\Models\User

# Config

- Thêm hành động cho từng cột của bảng
action.php 

- Thêm compoennt sau bảng trong view
components.php

- Thêm tab cho bảng
tab.php

- Thêm các bảng cần kiểm tra khi thoát ra bắt xác nhận và không được sửa cùng 1 lúc
table.php

- Thay đổi class và method cho cá hành động copy, add, view
view.php

- Thêm dữ liệu cho quan hệ nhiều nhiều
orther_table.php
*: Có thể bắt event 'vanhenry.table.creatDataMapTable' để lấy thông tin trả về

# Thay đổi config khi tạo package mới
// Có thể sử dụng method này để push config cho quản trị khi tạo package mới
 ```
private function pushConfig()
    {   
        $arrayConfigAdminNeedEdit = ['action'];
        foreach($arrayConfigAdminNeedEdit as $config_table){
            $config_defaults = config(static::CONFIG_ADMIN_KEY.$config_table, []);
            $newArray = [];
            foreach ($config_defaults as $table => $value) {
                $newArray[$table] = $value;
            }
            $config_package_currents = config(static::CONFIG_KEY_START . $config_table, []);
            foreach ($config_package_currents as $table => $data) {
                $newArray[$table] = $data;
            }
            Config::set(static::CONFIG_ADMIN_KEY.$config_table, $newArray);
        }
    }

 ```