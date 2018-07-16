<?php

namespace Symbiote\SitemapPage;

use Page;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\TreeMultiselectField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DB;

/**
 * Class SitemapPage
 * @package Symbiote\SitemapPage
 */
class SitemapPage
    extends Page
{
    private static $db = [
        'PagesToDisplay' => "Enum('All, ChildrenOf, Selected', 'All')"
    ];

    private static $has_one = [
        'ParentPage' => SiteTree::class
    ];

    private static $many_many = [
        'PagesToShow' => SiteTree::class
    ];

    private static $table_name = 'SitemapPage';

    private static $allowed_children = [];

    private static $description = '(HTML) sitemap page generated from the site tree';

    private static $icon = 'symbiote/silverstripe-sitemap: client/images/sitemap_icon.gif';

    /**
     * @param mixed
     * @return boolean
     */
    public function canAddChildren($member = null)
    {
        return false;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        Requirements::javascript('symbiote/silverstripe-sitemap: client/javascript/SitemapPageAdmin.js');

        $fields = parent::getCMSFields();

        $fields->findOrMakeTab('Root.Sitemap', $this->fieldLabel('Sitemap'));
        $fields->addFieldsToTab('Root.Sitemap', array(
            new HeaderField( 'PagesToDisplayHeader', $this->fieldLabel('PagesToDisplay'), 2),
            new OptionSetField('PagesToDisplay', '', array(
                'All' => $this->fieldLabel('AllPages'),
                'ChildrenOf' => $this->fieldLabel('ChildrenOf'),
                'Selected' => $this->fieldLabel('Selected')
            )),
            new TreeDropdownField('ParentPageID', '', SiteTree::class),
            new TreeMultiselectField('PagesToShow', '', SiteTree::class)
        ));
        return $fields;
    }

    /**
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        return array_merge(parent::fieldLabels($includerelations), array(
            'Sitemap' => _t('SitemapPage.SITEMAP', 'Sitemap'),
            'PagesToDisplay' => _t('SitemapPage.PAGESTOSHOW', 'Pages To Show In The Sitemap'),
            'AllPages' => _t('SitemapPage.ALLPAGES', 'Display all pages which are displayed in the menu.'),
            'ChildrenOf' => _t('SitemapPage.CHILDRENOF', 'Display the children of a specific page.'),
            'Selected' => _t('SitemapPage.SELECTED', 'Display only the selected pages.')
        ));
    }

    /**
     * @return string
     */
    public function getSitemap(ArrayList $set = null) {
        if(!$set) $set = $this->getRootPages();

        if($set && count($set)) {
            $sitemap = '<ul>';

            foreach($set as $page) {
                if($page->ShowInMenus && $page->ID != $this->ID && $page->canView()) {
                    $sitemap .= sprintf (
                        '<li><a href="%s" title="%s">%s</a>',
                        $page->XML_val('Link'),
                        $page->XML_val('MenuTitle'),
                        $page->XML_val('Title')
                    );

                    if($children = $page->Children()) {
                        $sitemap .= $this->getSitemap($children);
                    }

                    $sitemap .= '</li>';
                }
            }

            return $sitemap .'</ul>';
        }
    }

    /**
     * @return DataList
     */
    public function SitemapPages()
    {
        switch ($this->PagesToDisplay) {
            case 'ChildrenOf':
                return SiteTree::get()->filter([
                    'ShowInMenus' => 1,
                    'ParentID' => $this->ParentPageID
                ])->exclude('ID', $this->ID);
//                sprintf('"ParentID" = %d AND "ShowInMenus" = 1', $this->ParentPageID)
            case 'Selected':
                //return $this->PagesToShow($showInMenus);
                return $this->PagesToShow();
            default:
//                return DataObject::get(SiteTree::class, '"ParentID" = 0 AND "ShowInMenus" = 1');
                return SiteTree::get()->filter([
                    'ShowInMenus' => 1
                ]);
        }
    }

    /**
     * Creates a default {@link SitemapPage} object if one does not currently exist.
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        // Only run on ErrorPage class directly, not subclasses
        if (static::class !== self::class || !SiteTree::config()->create_default_pages) {
            return;
        }

        $page = SitemapPage::get()->first();
        $pageExists = !empty($page);
        if (!$pageExists) {
            $sitemap = new SitemapPage();
            $sitemap->Title = _t('SitemapPage.SITEMAP', 'Sitemap');
            $sitemap->Content = sprintf(
                '<p>%s</p>',
                _t('SitemapPage.DEFAULTCONTENT', 'Sitemap of the pages of this site.')
            );
            $sitemap->write();
//            $page->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);

            DB::alteration_message('Created default Sitemap page.', 'created');
        }
    }

}