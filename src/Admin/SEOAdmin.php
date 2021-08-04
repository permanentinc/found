<?php

namespace permanentinc\found\extensions;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Versioned\GridFieldArchiveAction;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;

class SEOAdmin extends ModelAdmin
{
    private static $url_segment = 'seo-editor';

    private static $menu_title = 'SEO Editor';

    private static $menu_icon_class = 'font-icon-p-search';

    public $showImportForm = false;

    public $showSeachForm = false;

    private static $page_length = 80;

    private static $managed_models = [
        SiteTree::class
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $grid = $form->Fields()->dataFieldByName('SilverStripe-CMS-Model-SiteTree');

        $gridField = $grid->getConfig();

        if ($gridField) {
            $gridField->removeComponentsByType(GridFieldAddNewButton::class);
            $gridField->removeComponentsByType(GridFieldPrintButton::class);
            $gridField->removeComponentsByType(GridFieldEditButton::class);
            $gridField->removeComponentsByType(GridFieldExportButton::class);
            $gridField->removeComponentsByType(GridFieldDeleteAction::class);
            $gridField->removeComponentsByType(GridFieldArchiveAction::class);
            $gridField->addComponent(new GridFieldEditableColumns());

            $gridField->getComponentByType(GridFieldDataColumns::class)->setDisplayFields(
                [
                    'Title' => 'Page Title',
                ]
            );

            $gridField->getComponentByType(GridFieldEditableColumns::class)->setDisplayFields(
                [
                    'FoundTitle' => 'Meta Title',
                    'FoundDescription' => 'Meta Description',
                    'FoundHide' => 'Hide page'
                ]
            );
        }

        return $form;
    }
}
