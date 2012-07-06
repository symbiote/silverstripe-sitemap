<% require themedCSS(SitemapPage) %>

<div class="typography">
	<h2>$Title</h2>
	
	$Content
	
	<% cached 'sitemap_page', ID, List(Page).Max(LastEdited) %>
		<% if Sitemap %>
			<div id="Sitemap">$Sitemap</div>
		<% end_if %>
	<% end_cached %>
	
	$Form
	$PageComments
</div>