{* $Header: /cvsroot/bitweaver/_bit_tickets/templates/edit_milestone.tpl,v 1.2 2009/01/20 22:18:07 dansut Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin milestones">
	{if $smarty.request.preview}
		<h2>Preview {$gContent->mInfo.title|escape}</h2>
		<div class="preview">
			{include file="bitpackage:milestones/milestone_display.tpl" page=`$gContent->mInfo.milestone_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.milestone_id}
				{tr}Edit {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New Record{/tr}
			{/if}
		</h1>
	</div>

	<div class="body">
		{form enctype="multipart/form-data" id="editmilestonesform"}
			{jstabs}
				{jstab}
					{legend legend="Edit/Create Tickets Record"}
						<input type="hidden" name="milestone[milestone_id]" value="{$gContent->mInfo.milestone_id}" />
						
						<div class="row">
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="60" maxlength="200" name="milestone[title]" id="title" value="{$gContent->mInfo.title|escape}" />
								{formhelp note="Brief and meaningful summary of a milestone."}
							{/forminput}
						</div>
						
						<div class="row">
							{formlabel label="Date from" for=""}
							{forminput}
								{html_select_date field_array="milestone" prefix="from_" time=$gContent->mDateFrom start_year="-5" end_year="+10"} {tr}at{/tr}&nbsp;
								<span dir="ltr">{html_select_time field_array="milestone" prefix="from_" time=$gContent->mDateFrom display_seconds=false}&nbsp;{$siteTimeZone}</span>
								{formhelp note="Select when milestone starts from."}
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Date to" for=""}
							{forminput}
								{html_select_date field_array="milestone" prefix="to_" time=$gContent->mDateTo start_year="-5" end_year="+10"} {tr}at{/tr}&nbsp;
								<span dir="ltr">{html_select_time field_array="milestone" prefix="to_" time=$gContent->mDateTo display_seconds=false}&nbsp;{$siteTimeZone}</span>
								{formhelp note="Select when to milestone ends."}
							{/forminput}
						</div>

						{textarea name="milestone[edit]"}{/textarea}

						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" name="save_milestone" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}

				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_tab_tpl"}
			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .milestones -->

{/strip}
