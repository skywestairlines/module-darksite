<div class="row spread contentsec">
		<% loop MainStatement %>
		<div class="col-md-8 col-sm-8 col-sx-12">
		<h3 class="text-uppercase">$PRPlace 
		</h3>
		$Content
		<br /><br />
		<div class="row">
			<% if showContacts %>
				<div class="col-md-6 col-sm-6 col-sx-12">
					<div class="columnHeader">
						<h3>Media Inquiries</h3>
					</div>
					<p><strong>Media Call:</strong><br/>
					$MediaInq</p>
				<% if showFamilyFone %>
					<p><strong>Information for Family Members</strong></p>
					<span style="margin-top:-12px;display:block;">$FamilyInq</span>
					<p><strong>Family Members Call:</strong><br/>$Familyfone</p> <% end_if %>
				</div>
			<% else %>
		
			<% end_if %>
			<% if showBriefing %>
			<div class="col-md-6 col-sm-6 col-sx-12">
				<div class="columnHeader">
					<h3>Media Briefing</h3>
				</div>
				<table class="table table-bordered">
					<tr class="active">
						<td><strong>Location:</strong></td>
						<td>$MediaLoc</td>
					</tr>
					<tr>
						<td><strong>Address:</strong></td>
						<td>$MediaAddress</td>
					</tr>
					<tr class="active">
						<td><strong>Date:</strong></td>
						<td>$MediaDate.Format(F d), $MediaDate.Format(Y)</td>
					</tr>
					<tr>
						<td><strong>Time:</strong></td>
						<td>$MediaTime</td>
					</tr>
					<tr class="active">
						<td><strong>Speakers:</strong></td>
						<td>$MediaSpeak</td>
					</tr>
				</table>
			</div>
		<% else %>
		<% end_if %>
			<div class="col-md-12 col-sm-12 col-sx-12">
		
			<% if isUpdated %>
				<i style="font-size:12px; color:#333;">Updated</i>
			<% end_if %> 
			
				<i style="font-size:12px;">$LastEdited.format(m/d/Y H:i) MT</i>
			
			</div>
		</div>
		</div>
		<% end_loop %>
		
		<% loop MainStatement %>
		<% if ShowTweet %>
			<div class="col-md-4 col-sm-4 col-sx-12">
				<div class="columnTopHeader">
					<h3>Twitter Feed</h3>
				</div>
				<% loop Top.GetTweets %>
					<% if First %>
					<p>$text</p>
					<% else %>
					<% end_if %>
				<% end_loop %>
			</div>
		<% end_if %>
		<% end_loop %>
		
		<% loop MainStatement %>
		<% if showReleases %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			<div class="columnHeader">
				<h3>Press Releases</h3>
			</div>
			<ul class="linklist">
				<% loop Top.DarkReleases %>
				<li class="pdf"><a href="$DarkRelease.URL" target="_blank">$Title - $Date.format(m/d/Y)</a></li>
				<% end_loop %>
			</ul>
		</div>
		<% else %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			&nbsp;
		</div>
		<% end_if %>
		<% end_loop %>
		
		<% loop MainStatement %>
		<% if showResources %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			<div class="columnHeader">
				<h3>Resources</h3>
			</div>
			<ul class="linklist">
			<% loop Top.DarkResources %>
			<li class="{$EvenOdd} pdf "><a href="$DarkResource.URL" target="_blank">$Title</a></li>
			<% end_loop %>
			</ul>
		</div>
		<% else %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			&nbsp;
		</div>
		<% end_if %>
		<% end_loop %>
		
		<% loop MainStatement %>
		<% if showPartners %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			<div class="columnHeader">
				<h3>Partner Info</h3>
			</div>
			<% loop Partners %>
			<% if DarkLogo %><img class="left" src="$DarkLogo.SetWidth(250).URL" style="max-width:250px;width:100%;" alt="$DarkLogo.Title"/><% end_if %>
			<p/>
			$PartnerContent
			<p><a href="$DarkPartnerLink" target="_blank">Click here</a> to access {$Title}' site.</p>
			<% end_loop %>
		</div>
		<% else %>
		<div class="col-md-4 col-sm-4 col-sx-12">
			&nbsp;
		</div>
		<% end_if %>
		<% end_loop %>
</div>
