{extends file='frontend/listing/listing_actions.tpl'}

{block name="frontend_listing_actions_class"}
	{if $sNumberPages && $sNumberPages > 1}
		<div class="blog--listing-actions">
	{/if}
{/block}

{block name="frontend_listing_actions_filter"}
	{include file="frontend/listing/actions/action-filter.tpl"}
{/block}

{block name='frontend_listing_actions_top'}{/block}

{block name="frontend_listing_actions_close"}
	{if $sNumberPages && $sNumberPages > 1}
		{$smarty.block.parent}
	{/if}
{/block}
