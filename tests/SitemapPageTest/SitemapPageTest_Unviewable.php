<?php

namespace Symbiote\SitemapPage\Tests\SitemapPageTest;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\TestOnly;

/**
 * @ignore
 */
class SitemapPageTest_Unviewable extends SiteTree implements TestOnly {

    public function canView($member = null) {
        return false;
    }

}
