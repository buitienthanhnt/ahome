<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Helper\Nan;
use App\Models\CategoryInterface;
use App\Models\WriterInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Constant\AttributeInterface;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // run: php artisan db:seed

        DB::table(WriterInterface::TABLE_NAME)->insert([
            [
                WriterInterface::ATTR_NAME => 'tha',
                WriterInterface::ATTR_NAME_ALIAS => 'tha',
                WriterInterface::ATTR_PHONE => '0702032201',
                WriterInterface::ATTR_EMAIL => 'tha@gmail.com',
                WriterInterface::ATTR_ACTIVE => 1,
                WriterInterface::ATTR_ADDRESS => '21b',
                WriterInterface::ATTR_DATE_OF_BIRTH => Carbon::now(),
            ]
        ]);

        DB::table(Nan::coreConfigTable())->insert([
            [AttributeInterface::ATTR_NAME => 'ip', AttributeInterface::ATTR_VALUE => '192.168.100.156', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'main', AttributeInterface::ATTR_VALUE => 'laravel1', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'domain', AttributeInterface::ATTR_VALUE => 'localhost', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'is_windown', AttributeInterface::ATTR_VALUE => false, AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'Authorization', AttributeInterface::ATTR_VALUE => null, AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'contact_address', AttributeInterface::ATTR_VALUE => 'Nam Tân-Trực Nội-Trực Ninh-Nam Định', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'contact_phone', AttributeInterface::ATTR_VALUE => '+84 702032201', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'head_title', AttributeInterface::ATTR_VALUE => 'Fast News', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'head_conten', AttributeInterface::ATTR_VALUE => 'thông tin nhanh và chính xác nhất', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'api_key', AttributeInterface::ATTR_VALUE => 'laravel1.com', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'default_image', AttributeInterface::ATTR_VALUE => 'https://www.gstatic.com/mobilesdk/180622_mobilesdk/clouds@2x.png', AttributeInterface::ATTR_TYPE => 'text'],
            [AttributeInterface::ATTR_NAME => 'custom_css', AttributeInterface::ATTR_VALUE => "\/* body{filter: grayscale(1)} */", AttributeInterface::ATTR_TYPE => 'text'],
        ]);

        DB::table(CategoryInterface::TABLE_NAME)->insert([
            [CategoryInterface::ATTR_NAME => 'thời sự', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'thoi-su', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'trong nước', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'trong-nuoc', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'quốc tế', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'quoc-te', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'công nghệ', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'cong-nghe', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'pháp luật', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 2, CategoryInterface::ATTR_URL_ALIAS => 'phap-luat', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'giải trí', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 2, CategoryInterface::ATTR_URL_ALIAS => 'giai-tri', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'tin mới', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'tin-moi', CategoryInterface::ATTR_TYPE => 'default'],
            [CategoryInterface::ATTR_NAME => 'chiến sự', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'chien-su', CategoryInterface::ATTR_TYPE => 'time_line'],
            [CategoryInterface::ATTR_NAME => 'thời tiết', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'thoi-tiet', CategoryInterface::ATTR_TYPE => 'time_line'],
            [CategoryInterface::ATTR_NAME => 'kinh tế', CategoryInterface::ATTR_ACTIVE => 1, CategoryInterface::ATTR_PARENT_ID => 0, CategoryInterface::ATTR_URL_ALIAS => 'kinh-te', CategoryInterface::ATTR_TYPE => 'default'],
        ]);

        DB::table('config_categories')->insert([
            ['path' => 'top_category', 'value' => '7&2&3&4&8'],
            ['path' => 'center_category', 'value' => '7&1&6&5'],
        ]);
    }
}
