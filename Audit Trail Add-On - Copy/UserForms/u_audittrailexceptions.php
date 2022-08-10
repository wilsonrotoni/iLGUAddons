<input type="hidden" id="series" name="series" value="<?php echo $series ?>" >

<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td nowrap="nowrap" width="1%"><label <?php genCaptionHtml($page->settings->boschema["T0"]["code"]) ?> >Tablename</label></td>
	<td nowrap="nowrap" width="60%">&nbsp;<input type"text" <?php genInputTextHtml($page->settings->boschema["T0"]["code"]) ?> /></td>
    <td nowrap="nowrap" width="1%">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["name"]) ?> >Description</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" size="50" <?php genInputTextHtml($page->settings->boschema["T0"]["name"]) ?> /></td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_fielddate"]) ?> >Field Date</label></td>
	<td nowrap="nowrap">&nbsp;<input type"text" size="50" <?php genInputTextHtml($page->settings->boschema["T0"]["u_fielddate"]) ?> /></td>
</tr>
<tr><td nowrap="nowrap"><label <?php genCaptionHtml($page->settings->boschema["T0"]["u_log"]) ?> >Include in Audit</label></td>
	<td nowrap="nowrap">&nbsp;<select <?php genSelectHtml($page->settings->boschema["T0"]["u_log"],array("loadudfenums","u_audittrailexceptions:log")) ?> ></select></td>
</tr>
<tr><td><label class="lblobjs">&nbsp;</label></td></tr>
<tr><td colspan="2"><?php $objGrids[0]->draw(true); ?></td></tr>
</table>