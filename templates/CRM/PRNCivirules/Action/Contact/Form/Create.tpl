<h3>{$ruleActionHeader}</h3>
<div class="crm-block crm-form-block">
	<div class="crm-section">
		<table class="mapping-table form-layout-compressed">
			<tr>
				<th>Custom Field</th>
				<th></th>
				<th>Civicrm Field</th>
			</tr>
			{foreach from=$customFields key=name item=desc}
			<tr>
				<td>{$desc}</td>
				<td>[{$name}]</td>
				<td>{$form.$name.html}</td>
			</tr>
			{/foreach}
			{foreach from=$calcFields key=src item=dest}
			<tr>
				<td>{$form.$src.html}</td>
				<td>[{$dest}]</td>
				<td>{$form.$dest.html}</td>
			</tr>
			{/foreach}
			
		</table>
		<!-- <a class="action-item crm-hover-button addCustomRow" href="#"><i
			class="crm-i fa-plus-circle" aria-hidden="true"></i> {ts}Add custom
			row{/ts}</a> -->
	</div>
</div>
<div class="crm-submit-buttons">{include
	file="CRM/common/formButtons.tpl" location="bottom"}</div>
{literal}
<script type="text/javascript">
	CRM.$(function($) {
		function addCustomRow() {
			var dataUrl = {/literal}"{crmURL q='snippet=4' h=0}"{literal};
			$.ajax({
				url : dataUrl,
				async : false,
				success : function(html) {
					$('.mapping-table tbody').append(html);
				}
			});
		}

		$('.addCustomRow').click(function(e) {
			addCustomRow();
		});
	});
</script>
{/literal}
