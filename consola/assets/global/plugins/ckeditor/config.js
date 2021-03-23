/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

		config.toolbar_Full =
      [
        ['Source','-','Preview','-','Templates'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        '/',
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Iframe','Link','Unlink','Anchor'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],
        ['FontSize'],
        ['TextColor','BGColor'],
        ['Maximize']
     ];
	  config.toolbar_Basic =
      [
        ['Source','-','Paste','PasteText','PasteFromWord'],
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['PasteText'],
        ['Undo','Redo'],
        ['NumberedList','BulletedList'],
        ['Link','Unlink','Anchor'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['FontSize','TextColor'],
		    ['Image','Flash','Table'],
        ['Maximize']
     ];
	  config.toolbar_Basic2 =
      [
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['PasteText'],
        ['NumberedList','BulletedList'],
        ['Link','Unlink','Anchor'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['FontSize','TextColor'],
		    /*['Image','Flash'],*/
        ['Maximize']
     ];
	  config.toolbar_Full2 =
      [
        ['Cut','Copy','Paste','PasteText','PasteFromWord'],
        ['Find','Replace','-','SelectAll','RemoveFormat'],
        ['Bold','Italic','Underline','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        '/',
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Iframe','Link','Unlink','Anchor'],
        ['Image','Flash','Table','HorizontalRule'],
        ['FontSize'],
        ['TextColor','BGColor'],
        ['Maximize']
     ];
     config.toolbar_Minimal =
      [
        ['Bold','Italic','Underline','Strike'],
        ['FontSize','TextColor'],
        ['Maximize']
     ];

    
    //COLOCAR ESTA PARTE COMENTADA NO CKEDITOR PRETENDIDO PARA LIMITAR O INPUT DE TEXTO
    // extraPlugins: 'wordcount,notification'


    config.wordcount = {
      // Whether or not you want to show the Paragraphs Count
      showParagraphs: false,

      // Whether or not you want to show the Word Count
      showWordCount: false,

      // Whether or not you want to show the Char Count
      showCharCount: true,

      // Whether or not you want to count Spaces as Chars
      countSpacesAsChars: true,

      // Whether or not to include Html chars in the Char Count
      countHTML: false,
      
      // Maximum allowed Word Count, -1 is default for unlimited
      maxWordCount: -1,

      // Maximum allowed Char Count, -1 is default for unlimited
      maxCharCount: 640,
    }

    config.enterMode = Number(2);

    //Para não perder os estilos ao guardar (por exemplo: class's na tabela de cookies)
    config.allowedContent = true;
};
