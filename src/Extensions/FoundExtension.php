<?php

namespace permanentinc\found\extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;

class FoundExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {

        /**
         * Remove the existing Metadata composite field
         */
        $fields->removeByName('Metadata');

        $fields->findOrMakeTab('Root.SEO', 'SEO');

        $fields->addFieldsToTab(
            'Root.SEO',
            [
                LiteralField::create('Found_Introduction', '<p class="foundIntroduction">Search engine optimization (SEO) allows you to improve your ranking in search results. Use these features to make it easier for users to find your page when they search for it.</p>'),
                LiteralField::create('Found_Example', '<p>Search Results Preview</p><div class="foundPreview"><span class="foundPreview__title [ js-found-preview-title ]" data-append=" - Your Site Title">Page Title - Your Site Title</span><span class="foundPreview__url [ js-found-preview-url ]">http://example.com</span><span class="foundPreview__description [ js-found-preview-description ]">This description will be automatically generated by search engines. To override that description, enter one below.</span></div>'),
                TextField::create('Found_Title', 'SEO Title (Optional)')
                    ->addExtraClass('[ js-found-title ]'),
                TextAreaField::create('Found_Description', 'SEO Title (Optional)') 
                    ->addExtraClass('[ js-found-description ]'),
                LiteralField::create('Found_Introduction', '<p>Search results typically show your SEO title and description. Your title is also the browser window title, and matches your title formats. Depending on the search engine, descriptions displayed can be 50 to 300 characters long. If you don’t add a title or description, search engines will use your page title and content.</p>'),
                CheckboxField::create('Found_Hide','Hide this page from search engine results'),
                LiteralField::create('Found_Image_Description', '<p class="foundIntroduction">Social networks typically show your social sharing image together with your SEO title and description. If you don’t add a social sharing image, we’ll use your social sharing logo or site logo instead.</p>'),
                UploadField::create('Found_Image', 'Alternate Social Sharing Image (Optional)'),
                


            ]
        );
    }
}
