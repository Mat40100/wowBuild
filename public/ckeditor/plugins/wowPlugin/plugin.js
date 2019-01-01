CKEDITOR.plugins.add('wowPlugin',
    {
        init: function (editor) {
            var pluginName = 'wowLink';
            editor.ui.addButton('Wow Item',
                {
                    label: 'Ajouter un item/spell/talent',
                    command: 'wow',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'icon.png'
                });
            var cmd = editor.addCommand('wow', { exec: wowItem });

            editor.ui.addButton('Wow-head',
                {
                    label: 'Wow-head',
                    command: 'OpenWindow',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'Wowhead.png'
                });
            var cmd = editor.addCommand('OpenWindow', { exec: wowHead });

            editor.ui.addButton('Size',
                {
                    label: 'Size',
                    command: 'SizeInput',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'size.png'
                });
            var cmd = editor.addCommand('SizeInput', { exec: sizeInput });

            editor.ui.addButton('Ul',
                {
                    label: 'Ul',
                    command: 'UlInput',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'UlInput.png'
                });
            var cmd = editor.addCommand('UlInput', { exec: UlInput });

            editor.ui.addButton('Ol',
                {
                    label: 'Ol',
                    command: 'OlInput',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'OlInput.png'
                });
            var cmd = editor.addCommand('OlInput', { exec: OlInput });

            editor.ui.addButton('Li',
                {
                    label: 'Li',
                    command: 'LiInput',
                    icon: CKEDITOR.plugins.getPath('wowPlugin') + 'LiInput.png'
                });
            var cmd = editor.addCommand('LiInput', { exec: LiInput });
        }
    });

function wowItem(editor) {

    if (CKEDITOR.env.ie) {
        selection = editor.getSelection().document.$.selection.createRange().text;
    } else {
        selection  = editor.getSelection().getNative();
    }
    editor.insertHtml('[item]' + selection + '[/item]')
}

function wowHead(e) {
    window.open('https://www.wowhead.com', 'MyWindow', 'width=800,height=700,scrollbars=no,scrolling=no,location=no,toolbar=no');
}

function sizeInput(editor) {
    if (CKEDITOR.env.ie) {
        selection = editor.getSelection().document.$.selection.createRange().text;
    } else {
        selection  = editor.getSelection().getNative();
    }
    editor.insertHtml('[size=XX]'+ selection +'[/size]');
}

function UlInput(editor) {
    if (CKEDITOR.env.ie) {
        selection = editor.getSelection().document.$.selection.createRange().text;
    } else {
        selection  = editor.getSelection().getNative();
    }
    editor.insertHtml('[ul]'+ selection +'[/ul]');
}

function OlInput(editor) {
    if (CKEDITOR.env.ie) {
        selection = editor.getSelection().document.$.selection.createRange().text;
    } else {
        selection  = editor.getSelection().getNative();
    }
    editor.insertHtml('[ol]'+ selection +'[/ol]');
}

function LiInput(editor) {
    if (CKEDITOR.env.ie) {
        selection = editor.getSelection().document.$.selection.createRange().text;
    } else {
        selection  = editor.getSelection().getNative();
    }
    editor.insertHtml('[li]'+ selection +'[/li]');
}