/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config ) {
	config.toolbar = 'Basic';

	config.toolbar_Full =
	[
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];

	config.toolbar_Basic =
	[
		['Bold', 'Italic','Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-'],
		['Image','Flash','Smiley','HorizontalRule'],
		['Source','Maximize'],
		'/',
		'/',
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Font','FontSize','TextColor','BGColor'],
	];
	
	config.font_names='宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names;
	config.filebrowserBrowseUrl = '/js/plugins/ckeditor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = '/js/plugins/ckeditor/ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = '/js/plugins/ckeditor/ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = '/js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';	
};
