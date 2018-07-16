<ul>
    <% loop $SitemapPages %>
        <li>
            <a href="$Link" title="$Title.ATT">$MenuTitle</a>
            <% if $Children %><% include Sitemap/SitemapListing SitemapPages=$Children %><% end_if %>
        </li>
    <% end_loop %>
</ul>