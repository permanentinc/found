<?php

namespace permanentinc\found\controllers;

use permanentinc\found\helpers\OpenAI;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Security\Permission;
use SilverStripe\SiteConfig\SiteConfig;

class FoundController extends Controller
{

    private static $allowed_actions = [
        'updateFoundtags',
        'assistedContent'
    ];

    public function init()
    {
        parent::init();

        if (!Permission::check('CMS_ACCESS')) {
            echo $this->response(null, 'You do not have permission to access this resource', 403, 'error');
            exit;
        }
    }

    public function updateFoundtags(HTTPRequest $request)
    {
        $fieldName = $request->getVar('fieldName');

        $page = SiteTree::get_by_id($request->getVar('id'));

        switch ($fieldName) {
            case 'FoundTitle':
                $page->FoundTitle = $request->postVar('value');
                break;
            case 'FoundDescription':
                $page->FoundDescription = $request->postVar('value');
                break;
            case 'FoundHide':
                $page->FoundHide = $request->postVar('value');
                break;
        }

        $page->write();        
        return $this->response(null, 'Updated', 200, 'ok');
    }
    
    
    public function assistedContent(HTTPRequest $request)
    {
        if (!SiteConfig::current_site_config()->FoundOpenAIAccessToken) {
            return $this->response(null, 'To use this feature you must first set up an OpenAI Access Token under site settings.', 200, 'error');
        }

        if (!$prompt = $request->requestVar('prompt')) {
            return $this->response(null, 'Please provide a prompt', 200, 'error');
        }

        $requestPrompt = [
            'Generate a meta-description for a web page',
            '(do not mention you are robot or AI)++',
            '(no not mention who you are)++',
            '(must be between 70 and 190 characters long, unless instructions say otherwise)++',
            '(keep meta-description within the subject)++',
            '(use this number as a seed: ' . time() . ')++',
            '(do not write any code)++',
            '(response must be text only)++',
        ];

        if ($tone = $request->requestVar('tone')) {
            $requestPrompt[] = '(must use voice tone: ' . $tone . ')++';
        }

        if ($instructions = $request->requestVar('instructions')) {
            $requestPrompt[] = '(must follow these instructions: ' . $instructions . ')++';
        }

        $requestPrompt[] = 'Meta-description subject: ' . $prompt;

        if (!$suggestion = OpenAI::chatRequest(implode(', ', $requestPrompt))) {
            return $this->response(null, 'Sorry, I could not generate a meta-description right now. Please, try again.', 200, 'error');
        }

        return $this->response([
            'suggestion' => $suggestion,
            'original_prompt' => $prompt,
            'tone' => $tone,
            'instructions' => $instructions
        ], 'Generated', 200, 'ok');

    }


    public function response($data = null, $message = null, $code = 200, $status = 'ok')
    {
        $this->getResponse()->setStatusCode($code);

        $this->getResponse()->addHeader('Content-Type', 'application/json');

        return json_encode([
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

}
