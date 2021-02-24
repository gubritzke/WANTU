/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function( config ) {
	//config.width = 100%; 
	config.height = 300;  
	
};

$( function(){

	/* configuração apenas com as funcionalidades básicas */
	var config = {
		'toolbar': [
		    { name: 'colors', items: [ 'TextColor' ] },
			{ name: 'basicstyles', items: ['Bold','Italic','Underline','Strike'] },
			{ name: 'clipboard', items: ['Undo','Redo'] },
			{ name: 'paragraph', items: ['NumberedList','BulletedList'] },
			{ name: 'links', items: ['Link','Unlink'] },
			{ name: 'insert', items: ['Image', 'Table'] },
		],
	};
	
	$('.ckeditor').ckeditor(config);
});	

