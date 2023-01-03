<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'activated' => true,
        'created_at' => $faker->dateTime,
        'deleted_at' => null,
        'email' => $faker->email,
        'first_name' => $faker->firstName,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'last_login_at' => $faker->dateTime,
        'last_name' => $faker->lastName,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'updated_at' => $faker->dateTime,

    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => $faker->boolean(),
        'forbidden' => $faker->boolean(),
        'language' => $faker->sentence,
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CipherKey::class, static function (Faker\Generator $faker) {
    return [
        'description' => $faker->text(),
        'signature' => $faker->text(),
        'complete_structure' => $faker->text(),
        'used_chars' => $faker->text(),
        'cipher_type' => $faker->sentence,
        'key_type' => $faker->sentence,
        'used_from' => $faker->dateTime,
        'used_to' => $faker->dateTime,
        'used_around' => $faker->sentence,
        'folder_id' => $faker->sentence,
        'location_id' => $faker->sentence,
        'language_id' => $faker->sentence,
        'group_id' => $faker->sentence,
        'state_id' => $faker->sentence,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Location::class, static function (Faker\Generator $faker) {
    return [
        'continent' => $faker->sentence,
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CipherType::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\KeyType::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\CipherKeySimilarity::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Tag::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'type' => $faker->sentence,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Cryptogram::class, static function (Faker\Generator $faker) {
    return [
        'availability' => $faker->sentence,
        'category_id' => $faker->sentence,
        'day' => $faker->randomNumber(5),
        'description' => $faker->text(),
        'flag' => $faker->boolean(),
        'image_url' => $faker->sentence,
        'language_id' => $faker->sentence,
        'location_id' => $faker->sentence,
        'month' => $faker->randomNumber(5),
        'name' => $faker->firstName,
        'recipient_id' => $faker->sentence,
        'sender_id' => $faker->sentence,
        'solution_id' => $faker->sentence,
        'state_id' => $faker->sentence,
        'year' => $faker->randomNumber(5),


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Solution::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Person::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Language::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,


    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Category::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'parent_id' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,


    ];
});
