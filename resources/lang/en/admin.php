<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',

            //Belongs to many relations
            'roles' => 'Roles',

        ],
    ],
    'user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Repeat password',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
            'roles' => 'Roles'


        ],
    ],

    'cipher-key' => [
        'title' => 'Cipher Keys',

        'actions' => [
            'index' => 'Cipher Keys',
            'create' => 'New Cipher Key',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'description' => 'Description',
            'signature' => 'Signature',
            'complete_structure' => 'Complete structure',
            'used_chars' => 'Used chars',
            'cipher_type' => 'Cipher type',
            'key_type' => 'Key type',
            'used_from' => 'Used from',
            'used_to' => 'Used to',
            'used_around' => 'Used around',
            'folder' => 'Folder',
            'fond' => 'Fond',
            'archive' => 'Archive',
            'new_folder' => 'New folder',
            'new_fond' => 'New fond',
            'new_archive' => 'New archive',
            'location' => 'Location',
            'language' => 'Language',
            'group' => 'Group',
            'state_id' => 'State',
            'user' => 'User',
            'new_user' => 'New user',
            'is_main_user' => 'Is main user?',
            'add_user' => 'Add user',
            'add_image' => 'Add image',
            'has_instructions' => 'Has instructions?',
            'image_structure' => 'Key structure on image',
            'image' => 'Image',
            'tags' => 'Tags',
            'images' => 'Images',
            'state' => 'State',
            'date' => 'Date',
            'update_state' => 'Update state',
            'note' => 'Note',
            'actual_state' => 'Actual state'

        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
