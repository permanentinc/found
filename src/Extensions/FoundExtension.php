<?php

namespace permanentinc\found\extensions;

use SilverStripe\i18n\i18n;
use SilverStripe\Assets\Image;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\AssetAdmin\Forms\UploadField;

class FoundExtension extends DataExtension
{

    private static $db = [
        'FoundTitle'       => 'Varchar(255)',
        'FoundDescription' => 'Text',
        'FoundTwitterUser' => 'Varchar(255)',
        'FoundHide'        => 'Boolean'
    ];

    private static $has_one = [
        'FoundImage' => Image::class
    ];

    private static $owns = [
        'FoundImage'
    ];

    public function updateCMSFields(FieldList $fields)
    {

        $fields->removeByName('Metadata');

        $fields->findOrMakeTab('Root.SEO', 'SEO');

        $nominalTitle = SiteConfig::current_site_config()->Title;
        $nominalDescription = 'This description will be automatically generated by search engines. To override that description, enter one below.';

        $pageTitle = $this->owner->FoundTitle ?: 'Page Title';

        $URL = preg_replace('#^https?://#', '', $this->owner->AbsoluteLink());
        $URL = rtrim($URL, '/');
        $image = $this->owner->FoundImage()->exists() ? $this->owner->FoundImage()->Fill(1200, 628)->URL : '';

        $fields->addFieldsToTab(
            'Root.SEO',
            [
                LiteralField::create('FoundIntroduction', '<p class="foundIntroduction">Search engine optimization (SEO) allows you to improve your ranking in search results. Use these features to make it easier for users to find your page when they search for it.</p>'),
                LiteralField::create(
                    'FoundExample',
                    '<p>Search Results Preview</p>
                    <div class="foundPreview">
                    <span class="foundPreview__title [ js-found-preview-title ]" data-nominal="' . $pageTitle . ' - ' . $nominalTitle . '" data-append=" - ' . $nominalTitle . '">' . $pageTitle . ' - ' . $nominalTitle . '</span>
                    <span class="foundPreview__url [ js-found-preview-url ]">' . $this->owner->AbsoluteLink() . '</span>
                    <span class="foundPreview__description [ js-found-preview-description ]" data-nominal="' . $nominalDescription . '">' . $nominalDescription . '</span>
                    </div>'
                ),
                TextField::create('FoundTitle', 'SEO Title (Optional)')->addExtraClass('[ js-found-title ]'),
                TextareaField::create('FoundDescription', 'SEO Description (Optional)')->addExtraClass('[ js-found-description ]'),
                LiteralField::create('FoundIntroduction', '<p>Search results typically show your SEO title and description. Your title is also the browser window title, and matches your title formats. Depending on the search engine, descriptions displayed can be 50 to 300 characters long. If you don’t add a title or description, search engines will use your page title and content.</p>'),
                LiteralField::create('FoundImage_Description', '<p class="foundIntroduction">Social networks typically show your social sharing image together with your SEO title and description. If you don’t add a social sharing image, we’ll use your social sharing logo or site logo instead. <a href="https://developers.facebook.com/tools/debug/sharing/?q=' . $this->owner->AbsoluteLink() . '" target="_blank">Facebook (fetch latest preview)</a></p>'),
                UploadField::create('FoundImage', 'Alternate Social Sharing Image (Optional)'),
                LiteralField::create('FoundImagePreview', '
                <p>Facebook Share Preview</p>
                <div class="foundSocialPreview">
                    <div class="foundSocialPreview__image">
                        <p>Add an image and save to preview</p>
                        <div class="foundSocialPreview__image__background [ js-found-preview-image ]" style="background-image:url(' . $image . ')"></div>
                    </div>
                    <div class="foundSocialPreview__copy">
                        <div class="foundSocialPreview__copy__url">' . $URL . '</div>
                        <div class="foundSocialPreview__copy__title [ js-found-preview-title ]" data-nominal="' . $pageTitle . ' - ' . $nominalTitle . '" data-append=" - ' . $nominalTitle . '">' . $pageTitle . ' - ' . $nominalTitle . '</div>
                        <div class="foundSocialPreview__copy__description [ js-found-preview-description ]" data-nominal="' . $nominalDescription . '">' . $nominalDescription . '</div>
                    </div>
                </div>'),
                LiteralField::create('Found_Spacer', '<p>&nbsp;</p>'),

                CheckboxField::create('FoundHide', 'Hide this page from search engine results'),
                // TextField::create('FoundTwitterUser', 'Twitter Username (Optional)')->setAttribute('placeholder', 'eg. @username'),
            ]
        );
    }

    public function FoundTags()
    {

        try {
            $created = new \DateTime($this->owner->Created);
            $created = $created->format('c');
        } catch (\Exception $e) {
        }

        try {
            $lastEdited = new \DateTime($this->owner->LastEdited);
            $lastEdited = $lastEdited->format('c');
        } catch (\Exception $e) {
        }

        $siteConfig = SiteConfig::current_site_config();
        $siteName = $siteConfig->Title != 'Your Site Name' ? $siteConfig->Title : false;

        $output = (string)ArrayData::create([
            'Title' => ($siteName ? $siteName . ' - ' : '') . ($this->owner->FoundTitle ?: $this->owner->Title),
            'Description' => $this->owner->FoundDescription,
            'AbsoluteURL' => $this->owner->AbsoluteLink(),
            'Locale' => i18n::get_locale(),
            'SiteName' => $siteName,
            'TwitterUser' => $this->owner->FoundTwitterUser,
            'Image' => $this->owner->FoundImage()->exists() ? $this->owner->FoundImage() : $siteConfig->FoundImage(),
            'Created' => isset($created) ? $created : $this->owner->Created,
            'LastEdited' => isset($lastEdited) ? $lastEdited : $this->owner->LastEdited,
            'SSL' => strtolower(Director::protocol()) == 'https'
        ])->renderWith('FoundTags');

        if ($this->owner->hasMethod('OverrideURL') && $this->owner->OverrideURL()) {
            $output = str_replace($this->owner->AbsoluteLink(), $this->owner->OverrideURL(), $output);            
        }

        return DBField::create_field('HTMLText', $output);

    }
}
