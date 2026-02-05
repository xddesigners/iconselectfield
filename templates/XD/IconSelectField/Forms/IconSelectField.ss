<% loop $Groups %>
	<details class="icon-select-group">
		<summary><h4>$Name</h4></summary>

		<ul $AttributesHTML>
			<% loop $Options %>
				<li class="$Class">
					<input id="$ID" class="radio" name="$Name" type="radio" value="$Value"
						<% if $isChecked %> checked<% end_if %>
						<% if $isDisabled %> disabled<% end_if %> />
					<label for="$ID">$Title</label>
				</li>
			<% end_loop %>
		</ul>
	</details>
<% end_loop %>