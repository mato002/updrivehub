<?php

return [

    'company_name' => env('RECRUITMENT_COMPANY_NAME', 'Acme Transport Ltd'),

    'hr_email' => env('RECRUITMENT_HR_EMAIL', 'hr@example.com'),

    'phone' => env('RECRUITMENT_PHONE', '+254 700 000 000'),

    'email' => env('RECRUITMENT_EMAIL', 'recruitment@example.com'),

    'address' => env('RECRUITMENT_ADDRESS', 'Nairobi, Kenya'),

    'max_upload_size_kb' => 5120,

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

];
