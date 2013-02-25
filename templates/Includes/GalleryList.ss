
<ul class="GalleryList block-grid three-up" data-clearing>
	<% loop Slides %>
		<% if $Image %>
			<li>
				<a class="thumb-link" href="$Image.URL">
					<img data-caption="$Title" src="$Image.CroppedImage(200,200).URL">
				</a>
			</li>
		<% end_if %>
	<% end_loop %>
</ul>
