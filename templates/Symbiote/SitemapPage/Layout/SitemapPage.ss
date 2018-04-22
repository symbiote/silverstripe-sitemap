<%--<% require themedCSS(SitemapPage) %>--%>

<div class="typography">
	<h2> Sitemappage $Title</h2>
	
	$Content
	
	<% cached 'sitemap_page', ID, List(Page).Max(LastEdited) %>
		<% if $SitemapPages %>
			<div id="Sitemap">
				<% include Sitemap/SitemapListing %>
			</div>
		<% end_if %>
	<% end_cached %>
	
	$Form
	$PageComments
</div>