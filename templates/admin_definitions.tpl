{* $Header$ *}
{strip}

<div class="floaticon">{bithelp}</div>

<div class="admin tickets">
	<div class="header">
		<h1>{tr}Admin Field Definitions{/tr}</h1>
	</div>

	<div class="body">
		{formfeedback error=$gContent->mErrors}

		{form legend="Field Definitions"}
			<input type="hidden" name="page" value="{$page}" />
			<table class="table data">
				<caption>{tr}Fields{/tr}</caption>
				<tr>
					<th title="Sort order">{tr}Sort order{/tr}</th>
					<th title="Title">{tr}Title{/tr}</th>
					<th title="Description">{tr}Description{/tr}</th>
					<th title="Use at creation">{tr}Use at creation{/tr}</th>
					<th title="Values">{tr}Values{/tr}</th>
					<th title="Actions">{tr}Actions{/tr}</th>
				</tr>
	        {foreach from=$fieldDefinitions item=fieldDef}
	        	<tr class="{cycle values="even,odd"}">
	        		<td>{$fieldDef.sort_order}</td>
	        		<td><input type="text" size="30" maxlength="40" id="field_title_{$fieldDef.def_id}" name="fieldDef[{$fieldDef.def_id}]['title']" value="{$fieldDef.title}" /></td>
	        		<td><input type="text" size="30" maxlength="100" name="fieldDef[{$fieldDef.def_id}]['description']" value="{$fieldDef.description}" /></td>
	        		<td>{html_checkboxes values="1" name="fieldDef[$fieldDef.def_id]['use_at_creation']" checked=$fieldDef.use_at_creation}</td>
	        		<td><ol>
	        		{foreach from=$fieldValues[$fieldDef.def_id] item=fieldValue}
	        		<li>{if $fieldValue.is_default}<strong>{/if}{$fieldValue.field_value}{if $fieldValue.is_default}</strong>{/if}</li>
	        		{foreachelse}{tr}No values defined{/tr}
	        		{/foreach}
	        		</ol></td>
	        		<td>
	
	                            <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_item=s&amp;tab=organise">{booticon iname="icon-cloud-download"  iforce=icon ipackage="icons"  iexplain="move down" style="float:right"}</a>
	                            <a href="{$smarty.const.TICKETS_PKG_URL}field_sort.php?sort_field={$fieldValue.field_id}&amp;move_items=n&amp;tab=organise">{booticon iname="icon-cloud-upload"  iforce=icon ipackage="icons"  iexplain="move up" style="float:right"}</a>
	                </td>
	             </tr>
	        {/foreach}
	       	</table>
	       	
			<div class="control-group submit">
				<input type="submit" class="btn" name="store_settings" value="{tr}Change preferences{/tr}" />
			</div>
	
		{/form}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}