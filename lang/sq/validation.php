<?php

return [

    'required' => 'Fusha :attribute është e detyrueshme.',
    'email' => 'Fusha :attribute duhet të jetë një adresë email e vlefshme.',
    'max' => [
        'string' => 'Fusha :attribute nuk mund të jetë më e gjatë se :max karaktere.',
        'numeric' => 'Fusha :attribute nuk mund të jetë më e madhe se :max.',
        'array' => 'Fusha :attribute nuk mund të ketë më shumë se :max elemente.',
        'file' => 'Fusha :attribute nuk mund të jetë më e madhe se :max kilobajte.',
    ],
    'min' => [
        'string' => 'Fusha :attribute duhet të jetë të paktën :min karaktere.',
        'numeric' => 'Fusha :attribute duhet të jetë të paktën :min.',
        'array' => 'Fusha :attribute duhet të ketë të paktën :min elemente.',
    ],
    'integer' => 'Fusha :attribute duhet të jetë numër i plotë.',
    'boolean' => 'Fusha :attribute duhet të jetë po ose jo.',
    'array' => 'Fusha :attribute duhet të jetë listë.',
    'in' => 'Zgjedhja e :attribute është e pavlefshme.',
    'image' => 'Fusha :attribute duhet të jetë një imazh.',
    'url' => 'Fusha :attribute duhet të jetë një adresë URL e vlefshme.',
    'accepted' => 'Fusha :attribute duhet të ketë një tip të pranuar.',
    'unique' => 'Fusha :attribute është e zënë tashmë.',
    'confirmed' => 'Konfirmimi i :attribute nuk përputhet.',
    'date' => 'Fusha :attribute nuk është një datë e vlefshme.',
    'after' => 'Fusha :attribute duhet të jetë pas :date.',
    'after_or_equal' => 'Fusha :attribute duhet të jetë :date ose më vonë.',
    'before' => 'Fusha :attribute duhet të jetë para :date.',
    'exists' => 'Zgjedhja e :attribute është e pavlefshme.',

    'custom' => [
        'alpha_dash' => [
            'string' => 'Fusha :attribute mund të përmbajë vetëm shkronja, numra, vija dhe vija të nënvijës.',
        ],
        'alpha_num' => [
            'string' => 'Fusha :attribute mund të përmbajë vetëm shkronja dhe numra.',
        ],
    ],

    'attributes' => [
        'name' => 'emri',
        'email' => 'emaili',
        'password' => 'fjalëkalimi',
        'phone' => 'telefoni',
        'company' => 'kompania',
        'message' => 'mesazhi',
        'notes' => 'shënimet',
        'sector' => 'sektori',
        'battery_type' => 'lloji i baterive',
        'service_preference' => 'mënyra e shërbimit',
        'preferred_date' => 'data e preferuar',
        'fleet_size' => 'numri i baterive',
        'requirements' => 'kërkesat',
        'customer_name' => 'emri dhe mbiemri',
        'company_name' => 'kompania',
        'contact_person' => 'personi i kontaktit',
    ],

];
