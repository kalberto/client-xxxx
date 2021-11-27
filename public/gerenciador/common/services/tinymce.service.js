(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .service('editorConfig', ['$http', function() {

            this.setup = function(editor) {

            };

         
    

            this.inline = false;
            this.plugins = 'advlist autolink link code image lists charmap print preview fullscreen textcolor colorpicker media table autoresize paste';
            this.skin = 'lightgray';
            this.theme = 'modern';
            this.menubar = true;
            this.paste_as_text = true;
            this.toolbar = 'code undo redo | styleselect forecolor backcolor | bold italic underline strikethrough | alignleft aligncenter alignright justify | link image media | removeformat | lists';

            this.convert_newlines_to_brs = true;
            this.force_br_newlines = true;
            this.forced_root_block = '';
            this.elements = 'nourlconvert';
            this.convert_urls = false;
          
            this.menu = {
                        //file: {title: 'File', items: 'newdocument'},
                        edit: {title: 'Editar', items: 'code undo redo | cut copy paste pastetext | selectall'},
                        insert: {title: 'Inserir', items: 'link media | template hr | textcolor colorpicker'},
                        //view: {title: 'View', items: 'visualaid fullscreen'},
                        format: {title: 'Formatar', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat | textcolor colorpicker'},
                        table: {title: 'Tabelas', items: 'inserttable tableprops deletetable | cell row column'},
                        //tools: {title: 'Tools', items: 'spellchecker code forecolor backcolor textcolor colorpicker'}
                      };
        



        }]);

})();