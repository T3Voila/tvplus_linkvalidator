<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'TemplaVoilà! Plus - Link Validator',
    'description' => 'Allows the Link Validator to check links inside TemplaVoilà! Plus elements',
    'category' => 'plugin',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'author' => 'Alexander Opitz',
    'author_email' => 'opitz.alexander@googlemail.com',
    'author_company' => 'T3Voila Team',
    'constraints' => [
        'depends' => [
            'php' => '7.2.0-8.1.99',
            'typo3' => '8.7.0-11.5.99',
            'linkvalidator' => '8.7.0-12.4.99',
            'templavoilaplus' => '8.1.0-11.2.99',
        ],
    ],
];
