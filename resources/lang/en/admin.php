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
            'forbidden' => 'Blocked',
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
            'forbidden' => 'Blocked',
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
            'name' => 'Name',
            'complete_structure' => 'Complete structure',
            'used_chars' => 'Used chars',
            'category_id' => 'Category',
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
            'created_by' => 'Created by',
            'similar-cryptograms' => 'Paired cryptograms',
            'continent' => 'Continent'

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
        'cryptograms' => 'Cryptograms',
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

    'cryptogram' => [
        'title' => 'Cryptograms',

        'actions' => [
            'index' => 'Cryptograms',
            'create' => 'New Cryptogram',
            'edit' => 'Edit :name',
            'bulk-pair' => 'Bulk cryptograms and cipher keys pairing'
        ],

        'columns' => [
            'id' => 'ID',
            'availability' => 'Other availability',
            'category_id' => 'Category',
            'subcategory_id' => 'Subcategory',
            'day' => 'Day',
            'description' => 'Description',
            'flag' => 'Is BC?',
            'thumbnail_url' => 'Image url',
            'language_id' => 'Language',
            'location_id' => 'Location',
            'month' => 'Month',
            'name' => 'Name',
            'recipient_id' => 'Recipient',
            'sender_id' => 'Sender',
            'solution_id' => 'Solution',
            'state_id' => 'State',
            'year' => 'Year',
            'data' => [
                'types' => 'Data type',
                'title' => 'Title',
                'text' => 'Text',
                'link' => 'Link',
                'image' => 'Image'
            ],
            'group' => [
                'name' => 'Group name'
            ],
            'add_datagroup' => 'Add datagroup',
            'add_predefined' => 'Add predefined groups',
            'add_data' => 'Add data',
            'predefined_groups' => 'Predefined groups',
            'paired-keys' => 'Paired cipher keys',
            'thumbnail' => 'Thumbnail',
            'date' => 'Date',
            'date_around' => 'Date around',
            'availability_type' => 'Availability'

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

    'digitalized-transcription' => [
        'title' => 'Digit. Transcriptions',

        'actions' => [
            'index' => 'Digitalized Transcriptions',
            'create' => 'New Digitalized Transcription',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'digitalized_version' => 'Digitalized version',
            'note' => 'Note',
            'digitalization_date' => 'Digitalization date',
            'created_by' => 'Created by',
            'cipher-keys' => 'Cipher key',
            'keys' => 'Encryption keys'

        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
