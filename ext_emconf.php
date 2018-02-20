<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'TemplaVoilà! Plus - Link Validator',
    'description' => 'Allows the Link Validator to check links inside TemplaVoilà! Plus elements',
    'category' => 'plugin',
    'version' => '0.1.0',
    'state' => 'beta',
    'clearCacheOnLoad' => 1,
    'author' => 'Alexander Opitz',
    'author_email' => 'alexander.opitz@pluspol-interactive.de',
    'author_company' => 'PLUSPOL interactive GbR',
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.2.99',
            'typo3' => '7.6.0-9.2.99',
            'linkvalidator' => '7.6.0-9.2.99',
            'templavoilaplus' => '7.1.2-7.99.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Ppi\\Templavoilaplus\\Linkvalidator\\' => 'Classes/',
        ],
    ],
];
