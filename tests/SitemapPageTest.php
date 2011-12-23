<?php
/**
 * @package silverstripe-sitemap
 * @subpackage tests
 */
class SitemapPageTest extends FunctionalTest {

	public static $fixture_file = 'sitemap/tests/SitemapPageTest.yml';

	public function testShowAll() {
		$sitemap = new SitemapPage();

		$expected = array (
			$this->objFromFixture('SiteTree', 'home')->Link(),
			$this->objFromFixture('SiteTree', 'about')->Link(),
			$this->objFromFixture('SiteTree', 'staff')->Link(),
			$this->objFromFixture('SiteTree', 'history')->Link(),
			$this->objFromFixture('SiteTree', 'contact')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that all valid pages are shown in the sitemap.'
		);
	}

	public function testShowChildrenOf() {
		$sitemap = new SitemapPage();

		$sitemap->PagesToDisplay = 'ChildrenOf';
		$sitemap->ParentPageID   = $this->idFromFixture('SiteTree', 'about');

		$expected = array (
			$this->objFromFixture('SiteTree', 'staff')->Link(),
			$this->objFromFixture('SiteTree', 'history')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that displaying the children of pages works.'
		);
	}

	public function testShowSelected() {
		$sitemap = new SitemapPage();
		$sitemap->write();

		$sitemap->PagesToDisplay = 'Selected';
		$sitemap->PagesToShow()->add($this->objFromFixture('SiteTree', 'about'));
		$sitemap->PagesToShow()->add($this->objFromFixture('SiteTree', 'contact'));
		$sitemap->write();

		$expected = array (
			$this->objFromFixture('SiteTree', 'about')->Link(),
			$this->objFromFixture('SiteTree', 'staff')->Link(),
			$this->objFromFixture('SiteTree', 'history')->Link(),
			$this->objFromFixture('SiteTree', 'contact')->Link()
		);

		$this->assertEquals (
			$expected, HTTP::getLinksIn($sitemap->getSitemap()), 'Assert that showing selected pages & children works.'
		);
	}

	public function testShowInMenusRespected() {
		$sitemap  = new SitemapPage();
		$homePage = $this->objFromFixture('SiteTree', 'home');

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

/**
 * @ignore
 */
class SitemapPageTest_Unviewable extends SiteTree {

	public function canView() {
		return false;
	}

}