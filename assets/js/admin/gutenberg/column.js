( function( blocks, element ) {
    var el = element.createElement;
 
    var blockStyle = {
        backgroundColor: '#900',
        color: '#fff',
        padding: '20px',
    };
 

    
    blocks.registerBlockType( 'dvu/column-block', {
        title: 'dvu content right',
        icon: 'universal-access-alt',
        category: 'design',
        example: {},

        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'p',
            },
        },


        edit: function(props) {
            var content = props.attributes.content;

            function onChangeContent( newContent ) {
                props.setAttributes( { content: newContent } );
            }
            return el(
                RichText,
                {
                    tagName: 'p',
                    className: props.className,
                    onChange: onChangeContent,
                    value: content,
                }
            );
 

        },

        //asve
        save: function( props ) {
            return el( RichText.Content, {
                tagName: 'p', value: props.attributes.content,
            } );
        },


    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.editor,
) );

