<input type="hidden" <?php genInputHiddenDFHtml("docno") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_docdate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_expired") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_isreferred") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_patientid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_patientname") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_doctorid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_hci_to_address") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_hci_fr_address") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_a_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_a_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_b_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_b_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_c_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_c_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_d_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_d_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_e_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_e_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_f_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_f_hc") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_g_pf") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_oth_g_hc") ?> >
<input type="hidden" id="edtopt" name="edtopt" value="<?php echo $page->getvarstring("edtopt"); ?>">
<?php if ($page->getvarstring("edtopt")=="integrated") { ?>
<input type="hidden" <?php genInputHiddenDFHtml("u_startdate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_enddate") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_age") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_gender") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_memberid") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_membername") ?> >
<input type="hidden" <?php genInputHiddenDFHtml("u_membertype") ?> >
<?php } ?>