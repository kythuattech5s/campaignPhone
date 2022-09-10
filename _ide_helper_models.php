<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\District
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $code
 * @property int|null $province_id
 * @property string|null $lat Vĩ độ
 * @property string|null $long Kinh độ
 * @property int|null $crawled Đã lấy tọa độ
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|District newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|District newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|District query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereCrawled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|District whereProvinceId($value)
 */
	class District extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $keyword
 * @property string|null $vi_value
 * @property string|null $en_value
 * @property string|null $ko_value
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $act
 * @property int|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereEnValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereKoValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereViValue($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string|null $link Link
 * @property string|null $name Tên
 * @property string|null $icon Icon
 * @property int|null $ord Sắp xếp
 * @property int|null $act Kích hoạt
 * @property \Illuminate\Support\Carbon|null $created_at Ngày tạo
 * @property \Illuminate\Support\Carbon|null $updated_at Ngày sửa
 * @property int|null $menu_category_id Danh mục menu
 * @property int|null $parent Cha
 * @property-read \Illuminate\Database\Eloquent\Collection|Menu[] $childs
 * @property-read int|null $childs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MenuCategory[] $menuCategories
 * @property-read int|null $menu_categories_count
 * @property-read \App\Models\MenuCategory|null $menuCategory
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereMenuCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereOrd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 */
	class Menu extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MenuCategory
 *
 * @property int $id
 * @property string|null $name Tên
 * @property int|null $ord Sắp xếp
 * @property int|null $act Kích hoạt
 * @property \Illuminate\Support\Carbon|null $created_at Ngày tạo
 * @property \Illuminate\Support\Carbon|null $updated_at Ngày cập nhật
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $menus
 * @property-read int|null $menus_count
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereOrd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuCategory whereUpdatedAt($value)
 */
	class MenuCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Province
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $lat Vĩ độ
 * @property string|null $long Kinh độ
 * @property int|null $crawled
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|Province query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereCrawled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Province whereName($value)
 */
	class Province extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\QueueEmail
 *
 * @property int $id ID
 * @property string|null $title Tiêu đề email
 * @property string|null $content Nội dung
 * @property string|null $from Gửi từ
 * @property string|null $to_name
 * @property string|null $to Gửi tới
 * @property \Illuminate\Support\Carbon|null $created_at Thời gian tạo
 * @property \Illuminate\Support\Carbon|null $updated_at Thời gian cập nhật
 * @property int|null $status Trạng thái
 * @property int|null $count_error Sồ lần gửi thất bại
 * @property string|null $attach_file Danh sách file đính kèm
 * @property string|null $result Kết quả gửi
 * @property int|null $is_sms Là SMS
 * @property string|null $bcc BCC
 * @property string|null $cc CC
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereAttachFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereCountError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereIsSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereToName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QueueEmail whereUpdatedAt($value)
 */
	class QueueEmail extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Test
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Test newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Test newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Test query()
 */
	class Test extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $name Họ và tên
 * @property string|null $username Tài khoản đăng nhập
 * @property string $email Email
 * @property string|null $phone Số điện thoại
 * @property string|null $password Mật khẩu
 * @property string|null $remember_token Ghi nhớ đăng nhập
 * @property string|null $birthday Ngày sinh
 * @property int|null $gender Giới tính
 * @property string|null $avatar Ảnh đại diện
 * @property int|null $act Kích hoạt
 * @property string|null $token Xác nhận mã OTP
 * @property int|null $banned Đã bị banned
 * @property string|null $address Địa chỉ
 * @property \Illuminate\Support\Carbon|null $created_at Ngày tạo
 * @property \Illuminate\Support\Carbon|null $updated_at Ngày cập nhật
 * @property-read \App\Models\District|null $district
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Province|null $province
 * @property-read \App\Models\Ward|null $ward
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VLanguage
 *
 * @property string $keyword
 * @property string|null $vi_value
 * @property string|null $en_value
 * @property string|null $note
 * @property string|null $create_at
 * @property string|null $update_at
 * @property int|null $act
 * @property int|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage query()
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereCreateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereEnValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VLanguage whereViValue($value)
 */
	class VLanguage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VRoutes
 *
 * @property int $id
 * @property string|null $vi_name Tên tiếng việt
 * @property string|null $en_name Tên tiếng anh
 * @property string|null $controller Controller
 * @property string|null $table Bảng
 * @property int|null $map_id Map id
 * @property int|null $is_static Là link tĩnh
 * @property int|null $in_sitemap Được hiển thị trong sitemap
 * @property string|null $code Mã link tĩnh(để lấy link theo ngôn ngữ)
 * @property string|null $vi_link Link tiếng việt
 * @property string|null $en_link Link tiếng anh
 * @property string|null $vi_seo_title Tiêu đề seo tiếng việt
 * @property string|null $vi_seo_key Từ khóa seo tiếng việt
 * @property string|null $vi_seo_des Mô tả seo tiếng việt
 * @property string|null $en_seo_title Tiêu đề seo tiếng anh
 * @property string|null $en_seo_key Từ khóa seo tiếng anh
 * @property string|null $en_seo_des Mô tả seo tiếng anh
 * @property \Illuminate\Support\Carbon|null $created_at Thời gian tạo
 * @property \Illuminate\Support\Carbon|null $updated_at Thời gian cập nhật
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereEnLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereEnName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereEnSeoDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereEnSeoKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereEnSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereInSitemap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereIsStatic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereViLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereViName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereViSeoDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereViSeoKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VRoutes whereViSeoTitle($value)
 */
	class VRoutes extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ward
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $district_id
 * @property string|null $name
 * @property string|null $lat Vĩ độ
 * @property string|null $long Kinh độ
 * @property int|null $crawled Đã lấy tọa độ
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel act()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearch($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel fullTextSearchNoRelevance($columns, $term)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel ord()
 * @method static \Illuminate\Database\Eloquent\Builder|Ward query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel slug($slug, $table = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereCrawled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ward whereName($value)
 */
	class Ward extends \Eloquent {}
}

