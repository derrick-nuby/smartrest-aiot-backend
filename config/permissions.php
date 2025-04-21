<?php

return [
    'roles' => [
        'admin' => [
            'create',
            'read',
            'update',
            'delete',
            'manage_users',
            'manage_roles',
            'manage_permissions'
        ],
        'doctor' => [
            'read',
            'view_patients',
            'manage_patients',
            'view_appointments',
            'manage_appointments',
            'view_medical_records',
            'manage_medical_records'
        ],
        'patient' => [
            'read',
            'view_appointments',
            'manage_appointments',
            'view_medical_records'
        ],
        'customer' => [
            'read',
            'view_appointments',
            'manage_appointments'
        ]
    ]
];