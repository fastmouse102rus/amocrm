<?php 
class AmoCRM {
    private $subdomain;
    private $accessToken;

    // Конструктор класса
    public function __construct($subdomain, $accessToken)
    {
        $this->subdomain = $subdomain;
        $this->accessToken = $accessToken;
    }
    
    public function createContact($name, $email, $phone)
    {
        $contactData = [
            'name' => $name,
            'custom_fields_values' => [
                [
                    'field_id' => 678127,
                    'values' => [
                        [
                            'value' => $email,
                        ],
                    ],
                ],
                [
                    'field_id' => 678125,
                    'values' => [
                        [
                            'value' => $phone,
                        ],
                    ],
                ],
            ],
        ];

        $contactUrl = "https://{$this->subdomain}.amocrm.ru/api/v4/contacts";
        $contactData = json_encode([$contactData]);

        $contactHeaders = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json",
        ];

        $ch = $this->initCurl($contactUrl, "POST", $contactData, $contactHeaders);

        $contactResponse = curl_exec($ch);
        $contactHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($contactHttpCode === 200) {
            $contactData = json_decode($contactResponse, true);
            $contactId = $contactData['_embedded']['contacts'][0]['id'];
            return $contactId;
        } else {
            return false;
        }
    }

    // Метод для создания сделки и привязки контакта к ней
    public function createLead($contactId)
    {
        $leadData = [
            'name' => 'Заявка Хуснутдинов',
            '_embedded' => [
                'contacts' => [
                    [
                        'id' => $contactId,
                    ],
                ],
            ],
            
        ];

        $leadUrl = "https://{$this->subdomain}.amocrm.ru/api/v4/leads";
        $leadData = json_encode([$leadData]);

        $leadHeaders = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json",
        ];

        $ch = $this->initCurl($leadUrl, "POST", $leadData, $leadHeaders);

        $leadResponse = curl_exec($ch);
        $leadHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($leadHttpCode === 200) {
            return true;
        } else {
            return false;
        }
    }

    // Вспомогательный метод для инициализации cURL
    private function initCurl($url, $method, $data, $headers)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $ch;
    }
}
?>