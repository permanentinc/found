<?php

namespace permanentinc\found\extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\PasswordField;
use SilverStripe\Versioned\Versioned;
use SilverStripe\AssetAdmin\Forms\UploadField;

class FoundConfigExtension extends DataExtension
{

    private static $db = [
        'FoundOpenAIAccessToken' => 'Varchar(255)'        
    ];

    private static $has_one = [
        'FoundImage' => Image::class        
    ];

    public function updateCMSFields(FieldList $fields)
    {

        $fields->findOrMakeTab('Root.SEO', 'SEO');

        $fields->addFieldsToTab('Root.SEO', [
            UploadField::create('FoundImage', 'Fallback Social Sharing Image (Optional)')
                ->setDescription('If set, this will be used on pages that do not have am individual Social Sharing Image set under SEO.'),
            TextField::create('FoundOpenAIAccessToken', 'OpenAI Access Token')
                ->setDescription('If set, this will enable the assisted content functionality.')
        ]);
    }


    public function onBeforeWrite()
    {
        if (class_exists(Versioned::class)) {
            if ($this->owner->FoundImage()->exists()) {
                $this->owner->FoundImage()->publishSingle();
            }
        }
    }


}

