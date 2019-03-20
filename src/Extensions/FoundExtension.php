<?php

namespace permanentinc\found\extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\HTML;
use SilverStripe\Control\ContentNegotiator;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * FoundExtension
 */
class FoundExtension extends DataExtension
{

    /**
     * updateCMSFields
     *
     * @param FieldList $fields
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {

        /**
         * Remove the existing Metadata composite field
         */
        $fields->removeByName('Metadata');

        /**
         * Make the main SEO tab
         */
        $fields->findOrMakeTab('Root.SEO', 'SEO');

        $nominalTitle = SiteConfig::current_site_config()->Title;
        $nominalDescription = 'This description will be automatically generated by search engines. To override that description, enter one below.';

        /**
         * Add all of the relevent emelents and fields to the SEO tab
         */
        $fields->addFieldsToTab(
            'Root.SEO',
            [
                LiteralField::create('FoundIntroduction', '<p class="foundIntroduction">Search engine optimization (SEO) allows you to improve your ranking in search results. Use these features to make it easier for users to find your page when they search for it.</p>'),
                LiteralField::create('FoundExample', '<p>Search Results Preview</p>
                                                       <div class="foundPreview">
                                                        <span class="foundPreview__title [ js-found-preview-title ]" data-nominal="Page Title - ' . $nominalTitle . '" data-append=" - ' . $nominalTitle . '">Page Title - ' . $nominalTitle . '</span>
                                                        <span class="foundPreview__url [ js-found-preview-url ]">' . $this->owner->AbsoluteLink() . '</span>
                                                        <span class="foundPreview__description [ js-found-preview-description ]" data-nominal="' . $nominalDescription . '">' . $nominalDescription . '</span>
                                                       </div>'),
                TextField::create('FoundTitle', 'SEO Title (Optional)')->addExtraClass('[ js-found-title ]'),
                TextAreaField::create('FoundDescription', 'SEO Title (Optional)')->addExtraClass('[ js-found-description ]'),
                LiteralField::create('FoundIntroduction', '<p>Search results typically show your SEO title and description. Your title is also the browser window title, and matches your title formats. Depending on the search engine, descriptions displayed can be 50 to 300 characters long. If you don’t add a title or description, search engines will use your page title and content.</p>'),
                CheckboxField::create('FoundHide', 'Hide this page from search engine results'),
                LiteralField::create('FoundImage_Description', '<p class="foundIntroduction">Social networks typically show your social sharing image together with your SEO title and description. If you don’t add a social sharing image, we’ll use your social sharing logo or site logo instead.</p>'),
                UploadField::create('FoundImage', 'Alternate Social Sharing Image (Optional)')
            ]
        );
    }

    public function FoundTags($includeTitle = true)
    {
        $page = $this->owner;
        $tags = [];

        /**
         * Only append the Site title if it is not the default one
         */
        $siteTitle = SiteConfig::current_site_config()->Title;
        $appendedTitle = ($siteTitle === 'Your Site Name') ? '' : ' - ' . $siteTitle;
        $pageTitle = '';
        $pageDescription = '';

        if ($page->FoundTitle) {
            $pageTitle = $page->Title . $appendedTitle;
        } elseif (strtolower($includeTitle) != 'false') {
            $pageTitle = $page->obj('Title')->forTemplate() . $appendedTitle;
        }

        if ($page->FoundDescription) $pageDescription = $page->FoundDescription;

        /**
         * Standard page title & description
         */
        if ($includeTitle) $tags[] = HTML::createTag('title', [], $pageTitle);
        if ($pageDescription) $tags[] = HTML::createTag('meta', ['name' => 'description', 'content' => $pageDescription]);

        /**
         * Open Graph Tags
         */
        $tags[] = HTML::createTag('meta', ['http-equiv' => 'Content-Type', 'content' => 'text/html; charset=' . ContentNegotiator::config()->uninherited('encoding')]);
        $tags[] = HTML::createTag('meta', ['property' => 'og:locale', 'content' => i18n::get_locale()]);
        $tags[] = HTML::createTag('meta', ['property' => 'og:type', 'content' => 'website']);
        $tags[] = HTML::createTag('meta', ['property' => 'og:title', 'content' => $pageTitle]);
        $tags[] = HTML::createTag('meta', ['property' => 'og:url', 'content' => $this->owner->AbsoluteLink()]);
        $tags[] = HTML::createTag('meta', ['property' => 'og:site_name', 'content' => SiteConfig::current_site_config()->Title]);
        if ($pageDescription) $tags[] = HTML::createTag('meta', ['property' => 'og:description', 'content' => $pageDescription]);
        if ($page->FoundImage) {
            $tags[] = HTML::createTag('meta', ['property' => 'og:image', 'content' => 'xxxxx']);
            $tags[] = HTML::createTag('meta', ['property' => 'og:image:secure_url', 'content' => 'xxxxx']);
            $tags[] = HTML::createTag('meta', ['property' => 'og:image:width', 'content' => '1024']);
            $tags[] = HTML::createTag('meta', ['property' => 'og:image:height', 'content' => '512']);
        }

        /**
         * Twitter Tags
         */
        $tags[] = HTML::createTag('meta', ['property' => 'twitter:card', 'content' => 'summary_large_image']);
        $tags[] = HTML::createTag('meta', ['property' => 'twitter:image', 'content' => 'website']);
        $tags[] = HTML::createTag('meta', ['property' => 'twitter:site', 'content' => '@xxx']);
        $tags[] = HTML::createTag('meta', ['property' => 'twitter:creator', 'content' => '@xxx']);
        if ($pageDescription) $tags[] = HTML::createTag('meta', ['property' => 'twitter:description', 'content' => $pageDescription]);
        if ($includeTitle) $tags[] = HTML::createTag('meta', ['property' => 'twitter:title', 'content' => $pageTitle]);

        $tagString = implode("\n", $tags);

        $page->extend('MetaTags', $tagString);

        // return $tagString;

        return DBField::create_field(DBHTMLText::class, $tagString);
    }
}
