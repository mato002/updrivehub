<?php

return [

    'company_name' => env('RECRUITMENT_COMPANY_NAME', 'Acme Transport Ltd'),

    'hr_email' => env('RECRUITMENT_HR_EMAIL', 'hr@example.com'),

    'phone' => env('RECRUITMENT_PHONE', '+254 700 000 000'),

    'email' => env('RECRUITMENT_EMAIL', 'recruitment@example.com'),

    'address' => env('RECRUITMENT_ADDRESS', 'Nairobi, Kenya'),

    'max_upload_size_kb' => (int) env('RECRUITMENT_MAX_UPLOAD_SIZE_KB', 1048576),

    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],

    'backgrounds' => [
        'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1920&q=80',
        'https://images.pexels.com/photos/448361/pexels-photo-448361.jpeg?auto=compress&cs=tinysrgb&w=1920',
        'https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=1920&q=80',
    ],

    'vehicle_types' => [
        'saloon' => 'Saloon',
        'suv' => 'SUV',
        'pickup' => 'Pickup',
        'van' => 'Van',
        'minibus' => 'Minibus',
        'bus' => 'Bus',
        'truck' => 'Truck',
        'trailer' => 'Trailer',
        'tanker' => 'Tanker',
        'construction_equipment' => 'Construction Equipment',
        'psv_driver' => 'PSV Driver',
        'long_distance_driver' => 'Long Distance Driver',
    ],

    'licence_classes' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],

    'kenya_counties' => [
        'Baringo', 'Bomet', 'Bungoma', 'Busia', 'Elgeyo-Marakwet', 'Embu',
        'Garissa', 'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kericho',
        'Kiambu', 'Kilifi', 'Kirinyaga', 'Kisii', 'Kisumu', 'Kitui', 'Kwale',
        'Laikipia', 'Lamu', 'Machakos', 'Makueni', 'Mandera', 'Marsabit',
        'Meru', 'Migori', 'Mombasa', 'Murang\'a', 'Nairobi', 'Nakuru',
        'Nandi', 'Narok', 'Nyamira', 'Nyandarua', 'Nyeri', 'Samburu',
        'Siaya', 'Taita-Taveta', 'Tana River', 'Tharaka-Nithi', 'Trans Nzoia',
        'Turkana', 'Uasin Gishu', 'Vihiga', 'Wajir', 'West Pokot',
    ],

    'application_statuses' => [
        'submitted' => ['label' => 'Submitted', 'color' => 'blue'],
        'under_review' => ['label' => 'Under Review', 'color' => 'amber'],
        'shortlisted' => ['label' => 'Shortlisted', 'color' => 'green'],
        'rejected' => ['label' => 'Rejected', 'color' => 'red'],
        'hired' => ['label' => 'Hired', 'color' => 'emerald'],
    ],

    'document_fields' => [
        'id_front' => ['path' => 'id_front_path', 'label' => 'National ID (Front)'],
        'id_back' => ['path' => 'id_back_path', 'label' => 'National ID (Back)'],
        'selfie' => ['path' => 'selfie_path', 'label' => 'Passport Selfie'],
        'licence' => ['path' => 'licence_path', 'label' => 'Driving Licence'],
        'cv' => ['path' => 'cv_path', 'label' => 'Curriculum Vitae'],
        'good_conduct' => ['path' => 'good_conduct_path', 'label' => 'Certificate of Good Conduct'],
        'medical' => ['path' => 'medical_path', 'label' => 'Medical Certificate'],
        'recommendation' => ['path' => 'recommendation_path', 'label' => 'Recommendation Letter'],
        'defensive_driving' => ['path' => 'defensive_driving_path', 'label' => 'Defensive Driving Certificate'],
    ],

    'admin_demo_logins' => (bool) env('ADMIN_DEMO_LOGINS', env('APP_ENV', 'production') === 'local'),

    'admin_demo_password' => env('ADMIN_DEMO_PASSWORD', env('ADMIN_PASSWORD', 'password')),

    'demo_admin_accounts' => [
        [
            'name' => 'Super Admin',
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'role' => 'Administrator',
            'icon' => 'fa-user-shield',
            'accent' => 'brand',
        ],
        [
            'name' => 'HR Manager',
            'email' => 'hr.manager@example.com',
            'role' => 'HR Manager',
            'icon' => 'fa-users-gear',
            'accent' => 'emerald',
        ],
        [
            'name' => 'Recruiter',
            'email' => 'recruiter@example.com',
            'role' => 'Recruiter',
            'icon' => 'fa-user-tie',
            'accent' => 'violet',
        ],
    ],

];
