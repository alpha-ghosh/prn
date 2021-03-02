<h3>{$ruleActionHeader}</h3>
<div
	class="crm-block crm-form-block">
	<div class="crm-section">
		<table class="form-layout-compressed">
			<tr>
				<td class="label">{$form.description.label}</td>
				<td>{$form.description.html}</td>
			</tr>
			<tr>
				<td class="label">{$form.count_max.label}</td>
				<td>{$form.count_max.html|crmReplace:type:number}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>{$form.is_active.html} {$form.is_active.label}</td>
			</tr>
			<tr>
				<td class="label">{$form.active_on.label}</td>
				<td>{$form.active_on.html}</td>
			</tr>
			<tr>
				<td class="label">{$form.expire_on.label}</td>
				<td>{$form.expire_on.html}</td>
			</tr>
			<tr>
				<td class="label">{$form.amount.label}</td>
				<td>{$form.amount.html|crmReplace:class:'crm-form-text six'}
					{$form.amount_type.html}</td>
			</tr>
			{if $form.memberships}
			<tr class="crm-discount-item-form-block-memberships">
				<td class="label">{$form.memberships.label}</td>
				<td>{$form.memberships.html}<br /></td>
			</tr>
			{/if}
			<tr>
				<td class="label">{$form.save_as.label}</td>
				<td>{$form.save_as.html}</td>
			</tr>
		</table>
	</div>
</div>
<div class="crm-submit-buttons">{include
	file="CRM/common/formButtons.tpl" location="bottom"}</div>