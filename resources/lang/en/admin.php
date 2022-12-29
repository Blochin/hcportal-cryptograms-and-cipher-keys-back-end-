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
            'actual_state' => 'Actual state',
            'created_by' => 'Created by'

        ],
    ],

    'location' => [
        'title' => 'Locations',

        'actions' => [
            'index' => 'Locations',
            'create' => 'New Location',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'continent' => 'Continent',
            'name' => 'Name',

        ],
    ],

    'cipher-type' => [
        'title' => 'Cipher Types',

        'actions' => [
            'index' => 'Cipher Types',
            'create' => 'New Cipher Type',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',

        ],
    ],

    'key-type' => [
        'title' => 'Key Types',

        'actions' => [
            'index' => 'Key Types',
            'create' => 'New Key Type',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',

        ],
    ],

    'cipher-key-similarity' => [
        'title' => 'Key Similarities',

        'actions' => [
            'index' => 'Cipher Key Similarities',
            'create' => 'New Cipher Key Similarity',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'cipher_keys' => 'Similar Cipher keys'

        ],
    ],

    'sidebar' => [
        'cipherkeys' => 'Cipher keys',
        'ciphers' => 'Ciphers',
        'general' => 'General'
    ],

    'tag' => [
        'title' => 'Tags',

        'actions' => [
            'index' => 'Tags',
            'create' => 'New Tag',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',

        ],
    ],

    'cipher' => [
        'title' => 'Ciphers',

        'actions' => [
            'index' => 'Ciphers',
            'create' => 'New Cipher',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'availability' => 'Availability',
            'category_id' => 'Category',
            'day' => 'Day',
            'description' => 'Description',
            'flag' => 'Is BC?',
            'image_url' => 'Image url',
            'language_id' => 'Language',
            'location_id' => 'Location',
            'month' => 'Month',
            'name' => 'Name',
            'recipient_id' => 'Recipient',
            'sender_id' => 'Sender',
            'solution_id' => 'Solution',
            'state_id' => 'State',
            'year' => 'Year',

        ],
    ],

    'solution' => [
        'title' => 'Solutions',

        'actions' => [
            'index' => 'Solutions',
            'create' => 'New Solution',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',

        ],
    ],

    'person' => [
        'title' => 'Persons',

        'actions' => [
            'index' => 'Persons',
            'create' => 'New Person',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',

        ],
    ],

    'language' => [
        'title' => 'Languages',

        'actions' => [
            'index' => 'Languages',
            'create' => 'New Language',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',

        ],
    ],

    'category' => [
        'title' => 'Categories',

        'actions' => [
            'index' => 'Categories',
            'create' => 'New Category',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',

        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
