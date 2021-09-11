<?php

namespace App\class;

use Mailjet\Client;
use Mailjet\Resources;

class Mailjet
{
    private $api_key = 'acf1b5b58b08619ee36c1cb7580bbcdb';
    private $api_key_secret = 'e5bb6c29374544f48e5caa04e5896905';

    public function send_register_confirm($to_email,$to_name)
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'alinzgohi@gmail.com',
                        'Name' => 'MoroccanExpress'
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3160984,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de l'adresse email",
                    'Variables' => [
                        'firstname' => $to_name,
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dump($response->getData());
    }

    public function send_order_confirm($to_email,$to_name,$total,$carrierName,$reference)
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'alinzgohi@gmail.com',
                        'Name' => 'MoroccanExpress'
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3161024,
                    'TemplateLanguage' => true,
                    'Subject' => "Confirmation de la commande",
                    'Variables' => [
                        'firstname' => $to_name,
                        'total' => ($total / 100).' DH',
                        'carrierName' => $carrierName,
                        'reference' => $reference
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dump($response->getData());
    }

    public function send_reset_password($to_email,$to_name,$url)
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'alinzgohi@gmail.com',
                        'Name' => 'MoroccanExpress'
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3165718,
                    'TemplateLanguage' => true,
                    'Subject' => "Recuperatin du mot de passe",
                    'Variables' => [
                        'firstname' => $to_name,
                        'Url' => $url,
                    ]
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dump($response->getData());
    }

}