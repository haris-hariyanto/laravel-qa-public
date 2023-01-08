<?php

return [
    'member' => [
        'Account' => [
            'Can change email' => 'member-account-email',
            'Can change password' => 'member-account-password',
            'Can change username' => 'member-account-username',
            'Can change avatar' => 'member-account-avatar',
        ],
    ],

    'admin' => [
        'Users' => [
            'Can view users' => 'admin-users-index',
            'Can create new users' => 'admin-users-create',
            'Can edit existing users' => 'admin-users-edit',
            'Can edit user passwords' => 'admin-users-password',
            'Can change user group' => 'admin-users-group',
            'Can delete users' => 'admin-users-delete',
        ],
        'Groups' => [
            'Can view groups' => 'admin-groups-index',
            'Can create new groups' => 'admin-groups-create',
            'Can edit existing groups' => 'admin-groups-edit',
            'Can delete groups' => 'admin-groups-delete',
            'Can edit group permissions' => 'admin-groups-permissions',
        ],
        'Administrators' => [
            'Can view administrators' => 'admin-admins-index',
            'Can add new administrators' => 'admin-admins-create',
            'Can delete administrators' => 'admin-admins-delete',
            'Can edit administrator permissions' => 'admin-admins-permissions',
        ],
        'Pages' => [
            'Can view pages' => 'admin-pages-index',
            'Can create new pages' => 'admin-pages-create',
            'Can edit existing pages' => 'admin-pages-edit',
            'Can delete pages' => 'admin-pages-delete',
        ],
        'Messages' => [
            'Can view messages' => 'admin-contacts-index',
        ],
    ],
];