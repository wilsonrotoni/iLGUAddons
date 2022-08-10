<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Cross-Browser Rich Text Editor (RTE)</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<!-- TinyMCE -->
	<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>
	<!--<script type="text/javascript" src="./tinymce/tinymce.min.js"></script>-->
	<script type="text/javascript">
	
tinymce.init({
        selector: "textarea",
        plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | insertfile insertimage",

        menubar: false,
        toolbar_items_size: 'small',

        style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],

        templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
        ]
});

		setTimeout("getContent()",500);
		
		function submitForm() {
			return tinymce.get('content').getContent();
		}
		
		function getContent() {
			tinymce.execCommand('mceReplaceContent',false,parent.getInput("u_remarks"));
			if (parent.getInput("docstatus")=="C") tinymce.activeEditor.getBody().setAttribute('contenteditable', false);
		}
		
	//window.onbeforeunload = function() {};
		
	</script>
	<!-- /TinyMCE -->
</head>
<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0">
<form method="post" action="somepage">
    <textarea name="content" style="width:100%;height:100%"></textarea>
</form>
</body>
</html>
