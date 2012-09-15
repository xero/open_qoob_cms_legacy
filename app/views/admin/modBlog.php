<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addBlog" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/modBlog/">
		<div class="row">
			<div class="lbl">Post Date:</div>
			<div class="inputs">
<?
	$draft = '';
	$now = '';
	$date = '';
	$msg = ' &lt; click to pick a date';
	switch ($postMenu) {
		case 'now':
			$now = 'checked="checked"';
		break;
		case 'date':
			$date = 'checked="checked"';
			$msg = date('m/d/Y g:i a', $txtDateTime);
		break;
		default:
			$draft = 'checked="checked"';
		break;
	}
?>				
				<input type="radio" name="postMenu" <?=$draft; ?> value="draft" onchange="toggleDate('draft')" /> save as draft
				<br class="clear" />
				<input type="radio" name="postMenu" <?=$now; ?> value="now" onchange="toggleDate('now')" /> post now
				<br class="clear" />
				<input type="radio" name="postMenu" <?=$date; ?> value="date" onchange="toggleDate('date')" /> select date
				<br class="clear" />
				<div id="pickDate"><input type="text" size="50" name="txtDateTime" id="txtDateTime" value="<?=$msg; ?>" /></div>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Title: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtTitle" title="page title" maxlength="300" value="<?=$txtTitle; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">SubTitle: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSubTitle" title="subtitle" maxlength="300" value="<?=$txtSubTitle; ?>"/>
			</div>
		</div>	
		<div class="row">
			<div class="lbl">URL: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtURL" id="txtURL" title="URL" maxlength="50" value="<?=$txtURL; ?>" onkeyup="urlInflector()"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">&nbsp;</div>
			<div class="inputs">
				<input type="radio" name="inflection" value="camel" onchange="urlInflector()" /> camel case
				<br class="clear" />
				<input type="radio" name="inflection" checked="checked" value="underscore" onchange="urlInflector()" /> underscored
			</div>
		</div>
		<div class="row">
			<div class="qoobformrow">
				<div class="bubble">
					<div id="URLexample"><?=QOOB_DOMAIN; ?>blog/<?=$txtURL; ?></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Excerpt: </div>
			<textarea class="inputwiderow textArearow" name="txtExcerpt" title="Excerpt" maxlength="1000"><?=$txtExcerpt; ?></textarea>
		</div>
		<div class="row">
			<div class="lbl">Content: </div>
			<textarea class="inputwiderow textArearow" name="txtBody" title="Body"><?=$txtBody; ?></textarea>
		</div>
		<div class="row">
			<div class="lbl">Categories: </div>
			<div class="inputs">
				<input type="button" name="btnAddTags" id="btnAddTags" value=" Create New Category " onclick="openForm('cats');" />
			</div>
		</div>
		<div id="blogCats" class="row">
			<div class="qoobformrow">
				<div class="bubble">
					<div class="lbl">&nbsp;</div>
					<div class="inputs">
						<div id="blogCat">loading categories...</div>
					</div>
					<br class="clear"/>
				</div>
			</div>
		</div>
		<div id="newCats" class="row" style="display: none;">
			<div class="qoobformrow">
				<div class="bubble">
					<div class="lbl">Parent: </div>
					<div class="inputs">
						<div id="newBlogCat">loading...</div>
					</div>
					<br/>&nbsp;
					<div class="lbl">Name: </div>
					<div class="inputs">
						<input class="" type="text" name="txtCatName" id="txtCatName" maxlength="50" title="Tag name" value=""/>
					</div>
					<br/>&nbsp;
					<div class="lbl">URL: </div>
					<div class="inputs">
						<input class="" type="text" name="txtCatURL" id="txtCatURL" maxlength="50" onchange="tagCatInflector('txtCatURL')" onkeyup="tagCatInflector('txtCatURL')" title="Tag URL" value=""/>
					</div>
					<br/>&nbsp;
					<div class="lbl">&nbsp; </div>
					<div class="inputs">
						<input type="button" name="btnAddTags" id="btnAddTags" value=" Add Category " onclick="addCat();" />
						<input type="button" name="btnAddTags" id="btnAddTags" value=" Cancel " onclick="closeForm('cats');" />
					</div>
					<br/>&nbsp;
					<br/>&nbsp;
					<br/>&nbsp;
				</div>
			</div>
		</div>		
		<div class="row">
			<div class="lbl">Tags: </div>
			<div class="inputs">
				<input type="hidden" size="50" name="txtTags" id="txtTags" />
				<input type="button" name="btnAddTags" id="btnAddTags" value=" Create New Tag " onclick="openForm('tags');" />
			</div>
		</div>
		<div id="newTags" class="row" style="display: none;">
			<div class="qoobformrow">
				<div class="bubble">
					<div class="lbl">Tag Name: </div>
					<div class="inputs">
						<input class="" type="text" name="txtTagName" id="txtTagName" maxlength="50" title="Tag name" value=""/>
					</div>
					<br/>&nbsp;
					<div class="lbl">Tag URL: </div>
					<div class="inputs">
						<input class="" type="text" name="txtTagURL" id="txtTagURL" maxlength="50" onchange="tagCatInflector('txtTagURL')" onkeyup="tagCatInflector('txtTagURL')" title="Tag URL" value=""/>
					</div>
					<br/>&nbsp;
					<div class="lbl">&nbsp; </div>
					<div class="inputs">
						<input type="button" name="btnAddTags" id="btnAddTags" value=" Add Tag " onclick="addTag();" />
						<input type="button" name="btnAddTags" id="btnAddTags" value=" Cancel " onclick="closeForm('tags');" />
					</div>
					<br/>&nbsp;
					<br/>&nbsp;
				</div>
			</div>
		</div>
		<div id="blogTags" class="row">
			<div class="qoobformrow">
				<div class="bubble">
					<div id="tagCloud">loading tags...</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnAdd" title=" Modify Blog " value=" Modify Blog " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
		<input type="hidden" name="theRealURL" id="theRealURL" value="<?=$txtURL; ?>" />
		<input type="hidden" name="post_id" id="post_id" value="<?=$post_id; ?>" />
	</form>
</div>
<br class="clear" /><br/>