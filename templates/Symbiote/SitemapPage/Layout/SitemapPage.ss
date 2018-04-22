<%--<% require themedCSS(SitemapPage) %>--%>
<% require css("symbiote/silverstripe-sitemap: client/css/sitemap_styling.css") %>

<div class="typography">
	<h2>$Title</h2>
	
	$Content
	
	<%--<% cached 'sitemap_page', ID, List(Page).Max(LastEdited) %>--%>
		<% if $SitemapPages %>
			<div id="Sitemap">
				<% include Sitemap/SitemapListing %>
			</div>
		<% end_if %>
	<%--<% end_cached %>--%>
	
	$Form
	$PageComments
</div>