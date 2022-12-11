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
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
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
