<?php

namespace Symbiote\SitemapPage\Tests;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\HTTP;
use SilverStripe\Dev\FunctionalTest;
use Symbiote\SitemapPage\SitemapPage;

/**
 * @package silverstripe-sitemap
 * @subpackage tests
 */
class SitemapPageTest extends FunctionalTest {

    protected static $fixture_file = 'SitemapPageTest.yml';

    protected static $extra_dataobjects = [
        SitemapPageTest\SitemapPageTest_Unviewable::class,
    ];

	protected static $use_draft_site = true;

	public function testShowAll() {
		$sitemap = new SitemapPage();

		$expected = array (
			$this->objFromFixture(SiteTree::class, 'home')->Link(),
			$this->objFromFixture(SiteTree::class, 'about')->Link(),
			$this->objFromFixture(SiteTree::class, 'staff')->Link(),
			$this->objFromFixture(SiteTree::class, 'history')->Link(),
			$this->objFromFixture(SiteTree::class, 'contact')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that all valid pages are shown in the sitemap.'
		);
	}

	public function testShowChildrenOf() {
		$sitemap = new SitemapPage();

		$sitemap->PagesToDisplay = 'ChildrenOf';
		$sitemap->ParentPageID   = $this->idFromFixture(SiteTree::class, 'about');

		$expected = array (
			$this->objFromFixture(SiteTree::class, 'staff')->Link(),
			$this->objFromFixture(SiteTree::class, 'history')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that displaying the children of pages works.'
		);
	}

	public function testShowSelected() {
		$sitemap = new SitemapPage();
		$sitemap->write();

		$sitemap->PagesToDisplay = 'Selected';
		$sitemap->PagesToShow()->add($this->objFromFixture(SiteTree::class, 'about'));
		$sitemap->PagesToShow()->add($this->objFromFixture(SiteTree::class, 'contact'));
		$sitemap->write();

		$expected = array (
			$this->objFromFixture(SiteTree::class, 'about')->Link(),
			$this->objFromFixture(SiteTree::class, 'staff')->Link(),
			$this->objFromFixture(SiteTree::class, 'history')->Link(),
			$this->objFromFixture(SiteTree::class, 'contact')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that showing selected pages & children works.'
		);
	}

	public function testShowInMenusRespected() {
		$sitemap  = new SitemapPage();
		$homePage = $this->objFromFixture(SiteTree::class, 'home');

		$this->assertContains (
			$homePage->Link(), HTTP::getLinksIn($sitemap->getSitemap()), 'The page is displayed by default.'
		);

		$homePage->ShowInMenus = false;
		$homePage->write();

		$this->assertNotContains (
			$homePage->Link(), HTTP::getLinksIn($sitemap->getSitemap()), 'The page is displayed by default.'
		);
	}

}
