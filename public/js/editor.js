function newEditor(id){
    return ClassicEditor
        .create( document.querySelector( id ), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'fontColor',
                    'fontBackgroundColor',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'undo',
                    'redo'
                ]
            },
            language: 'fr',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:full',
                    'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            licenseKey: '',
        } )
        .then( editor => {
            window.editor = editor;
        } )
        .catch( error => {
            console.error( 'Oups, une erreur s\'est produite !' );
            console.error( 'Veuillez signaler l’erreur suivante sur https://github.com/ckeditor/ckeditor5/issues avec l’identifiant de compilation et le tracé de la pile d’erreurs :' );
            console.warn( 'Build id: adlamaxygj03-c6qyv3wc8h' );
            console.error( error );
        } );
}
