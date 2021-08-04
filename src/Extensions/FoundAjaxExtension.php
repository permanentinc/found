<?php

namespace permanentinc\found\extensions;


use SilverStripe\Core\Extension;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;

class FoundAjaxExtension extends Controller
{

    private static $allowed_actions = [
        'updateFoundtags'
    ];

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

    public function updateFoundtags()
    {
        $request = $this->owner->getRequest();

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

        if ($page->write()) {
            return $this->response('Values updated');
        } else {
            return $this->error('Something went wrong', 500);
        }
    }
}
