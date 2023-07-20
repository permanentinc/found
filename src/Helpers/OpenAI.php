<?php

namespace permanentinc\found\helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use SilverStripe\SiteConfig\SiteConfig;

class OpenAI
{

    public static function chatRequest($prompt)
    {
        $response = self::gpt([[
            'role' => 'user',
            'content' => $prompt
        ]]);

        if ($response && isset($response['choices']) && (count($response['choices']) > 0)) {
            return $response['choices'][0]['message']['content'];
        }
    }

    public static function gpt($messages)
    {
        if (!$apiToken = SiteConfig::current_site_config()->FoundOpenAIAccessToken) {
            return null;
        }

        set_time_limit(0);
        
        $client = new Client();

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken
        ];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 1000
        ];

        $body = json_encode($data);

        try {

            $request = new Request('POST', 'https://api.openai.com/v1/chat/completions', $headers, $body);

            $res = $client->sendAsync($request)->wait();

            if ($data = json_decode($res->getBody(), true)) {
                return $data;
            }

        } catch (\Exception $e) {
        }   

        return null;                
    }

}