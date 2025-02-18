<?php

namespace App\Service;

use \Mailjet\Resources;
use Mailjet\Client;

class MailService
{
    private $mailjet;

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->mailjet = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
    }

    public function sendEmail(string $toEmail, string $toName, string $subject, string $content)
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "hedi.laater@gmail.com",
                        'Name' => "Rayhana"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $content
                ]
            ]
        ];

        $response = $this->mailjet->post(Resources::$Email, ['body' => $body]);
        return $response->success();
    }
}