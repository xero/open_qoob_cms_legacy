<div class="statsBox">
	<h3>Visits</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="visitsRange" id="visitsRange" onchange="getVisits()">
				<option value="1" selected="selected">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4">Everything</option>
			</select>
			<select name="visitsView" id="visitsView" onchange="getVisits()">
				<option value="1" selected="selected">Text</option>
				<option value="2">Graph</option>
			</select>
			<div id="visits">loading...</div>
			<br/>
		</div>
	</div>
</div>
<div class="statsBox">
	<h3>Browsers</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="browserData" id="browserData" onchange="getBrowsers()">
				<option value="1" selected="selected">Browser</option>
				<option value="2">Platform</option>
				<option value="3">Resolution</option>
				<option value="4">Flash</option>
			</select>
			<select name="browserRange" id="browserRange" onchange="getBrowsers()">
				<option value="1">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4" selected="selected">Everything</option>
			</select>
			<select name="browserView" id="browserView" onchange="getBrowsers()">
				<option value="1" selected="selected">Text</option>
				<option value="2">Graph</option>
			</select>
			<div id="browsers">loading...</div>
			<br/>
		</div>
	</div>
</div>
<div class="statsBox">
	<h3>Pages</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="pagesRange" id="pagesRange" onchange="getPages()">
				<option value="1" selected="selected">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4">Everything</option>
			</select>			
			<select name="pagesLimit" id="pagesLimit" onchange="getPages()">
				<option value="1" selected="selected">10 Results</option>
				<option value="2">25 Results</option>
				<option value="3">50 Results</option>
				<option value="4">75 Results</option>
				<option value="5">100 Results</option>
				<option value="6">All Results</option>
			</select>			
			<div id="pages">loading...</div>
			<br/>
		</div>
	</div>
</div>
<div class="statsBox">
	<h3>Referers</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="referrersRange" id="referrersRange" onchange="getReferrers()">
				<option value="1" selected="selected">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4">Everything</option>
			</select>			
			<select name="referrersLimit" id="referrersLimit" onchange="getReferrers()">
				<option value="1" selected="selected">10 Results</option>
				<option value="2">25 Results</option>
				<option value="3">50 Results</option>
				<option value="4">75 Results</option>
				<option value="5">100 Results</option>
				<option value="6">All Results</option>
			</select>
			<div id="referrers">loading...</div>
			<br/>
		</div>
	</div>
</div>
<div class="statsBox">
	<h3>Locations</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="locationsRange" id="locationsRange" onchange="getLocations()">
				<option value="1" selected="selected">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4">Everything</option>
			</select>
			<select name="locationsView" id="locationsView" onchange="getLocations()">
				<option value="1" selected="selected">Text</option>
				<option value="2">Graph</option>
			</select>			
			<div id="locations">loading...</div>
			<br/>
		</div>
	</div>
</div>
<div class="statsBox">
	<h3>Searches</h3>
	<div class="formbox">
		<div class="bubble">
			<select name="searchesRange" id="searchesRange" onchange="getSearches()">
				<option value="1" selected="selected">Last Month</option>
				<option value="2">Last 6 Months</option>
				<option value="3">Last Year</option>
				<option value="4">Everything</option>
			</select>			
			<select name="searchesLimit" id="searchesLimit" onchange="getSearches()">
				<option value="1" selected="selected">10 Results</option>
				<option value="2">25 Results</option>
				<option value="3">50 Results</option>
				<option value="4">75 Results</option>
				<option value="5">100 Results</option>
				<option value="6">All Results</option>
			</select>
			<div id="searches">loading...</div>
			<br/>
		</div>
	</div>
</div>
<br class="clear"/><br/>