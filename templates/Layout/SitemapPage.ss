<% require themedCSS(SitemapPage) %>

<div class="typography">
	<h2>$Title</h2>
	
	$Content
	
	<% if Sitemap %>
		<div id="Sitemap">$Sitemap</div>
	<% end_if %>
	
	$Form
	$PageComments
</div>