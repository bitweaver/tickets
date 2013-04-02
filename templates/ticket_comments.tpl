{strip}

	<div class="header">
		<h2>{tr}Comments{/tr}</h2>
	</div>

	<div class="body"{if !( $post_comment_request || $post_comment_preview )} id="editcomments"{/if}>

		{include file="bitpackage:liberty/comments_display_option_bar.tpl"}
		
		{foreach name=comments_loop key=key item=item from=$comments}
				{displaycomment comment="$item"}
        {/foreach}
		
		<div id="comment_{$gContent->mContentId}"></div>
		<div id="comment_{$gContent->mContentId}_footer"></div>
		
		{if $comments_ajax && $gBitUser->hasPermission( 'p_liberty_post_comments' )}
			<div class="control-group submit">
				<input type="submit" name="post_comment_request" value="{tr}Add Comment{/tr}" onclick="BitTicket.setLibertyComment();LibertyComment.attachForm('comment_{$gContent->mContentId}', '{$gContent->mContentId}', {if $gContent->mContentId}{$gContent->mContentId}{elseif $commentsParentId}{$commentsParentId}{else}null{/if})"/>
			</div>
		{/if}
		
		<div id="edit_comments" {if $comments_ajax}style="display:none"{/if}>
			{include file="bitpackage:liberty/comments_post_inc.tpl" post_title="Post Comment"}
		</div>

		{libertypagination ihash=$commentsPgnHash}
	</div><!-- end .body -->

{/strip}
