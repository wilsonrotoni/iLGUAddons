<input type="hidden" <?php genInputHiddenDFHtml("code") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_format") ?> >
<?php if ($page->getitemstring("u_format")=="rtf") { ?>
	<input type="hidden" <?php genInputHiddenDFHtml("u_remarks") ?> >
<?php } ?>