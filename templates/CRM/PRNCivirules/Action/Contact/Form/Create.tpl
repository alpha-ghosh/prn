<h3>{$ruleActionHeader}</h3>
<div class="crm-block crm-form-block">
	<div class="crm-section">
		<table class="form-layout-compressed">
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
		</table>
	</div>
</div>
<div class="crm-submit-buttons">{include
	file="CRM/common/formButtons.tpl" location="bottom"}</div>
