{* $Header$ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin tickets">
	{if $smarty.request.preview}
		<h2>Preview {$gContent->mInfo.title|escape}</h2>
		<div class="preview">
			{include file="bitpackage:tickets/ticket_display.tpl" page=`$gContent->mInfo.ticket_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.ticket_id}
				{tr}Edit {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New Record{/tr}
			{/if}
		</h1>
	</div>

	<div class="body">
		{form enctype="multipart/form-data" id="editticketsform"}
			{jstabs}
				{jstab}
					{legend legend="Edit/Create Tickets Record"}
						<input type="hidden" name="ticket[ticket_id]" value="{$gContent->mInfo.ticket_id}" />

						<div class="display ticket">
							<ul>
								{include file="edit_header_inc.tpl" fieldDefinitions=$fieldDefinitions fieldValues=$fieldValues milestones=$milestones gContent=$gContent}
							</ul>
							<div class="clear"></div>
						</div>

						<div class="row">
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="60" maxlength="200" name="ticket[title]" id="title" value="{$gContent->mInfo.title|escape}" />
								{formhelp note="Brief and meaningful summary of a ticket."}
							{/forminput}
						</div>

						{textarea name="ticket[edit]"}{/textarea}

						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" name="save_ticket" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}

				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_tab_tpl"}
			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .tickets -->

{/strip}
