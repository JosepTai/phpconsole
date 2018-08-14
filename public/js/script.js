$(document).ready(function () {
    $('#phpcTabs').tabs();
    $('.modal').modal();
    $('.select').formSelect();
    $('.fixed-action-btn').floatingActionButton();
    $('.tooltipped').tooltip();
    $('.sidenav').sidenav();
    $('.select2').select2();
});

const Spinner = `<div class="preloader-wrapper small animated fadeIn active">
                <div class="spinner-layer spinner-blue-only">
                  <div class="circle-clipper left">
                    <div class="circle"></div>
                  </div><div class="gap-patch">
                    <div class="circle"></div>
                  </div><div class="circle-clipper right">
                    <div class="circle"></div>
                  </div>
                </div>
            </div>`;

(function ($) {
    $.fn.setCursorToTextEnd = function () {
        this.focus();
        let initialVal = this.val();
        this.val('').val(initialVal);

        return this;
    }
}(jQuery));

function closeWindow() {
    close();

    return false;
}

function closeAndClearSession() {
    axios.post('clear-updates-session')
        .then(response => {
            close();
            console.log(response.data);
        }).catch(error => console.log(error.response));
}

// (function($){
//     $(window).on("load",function(){
//
//         $(".sidebar-inne").mCustomScrollbar({
//             theme:"minimal-dark"
//         });
//
//         $(".content-").mCustomScrollbar({
//             setHeight: 300,
//             theme:"minimal-dark"
//         });
//
//     });
// })(jQuery);

/**
 * Editor
 */

let activeTheme = AppSettings.theme;
let activeFont = AppSettings.app_font_family;
let activeFontSize = AppSettings.app_font_size;

let Editor = ace.edit("editor", {
    theme: "ace/theme/" + activeTheme,
    mode: "ace/mode/php",
    enableEmmet: true,
    showPrintMargin: false,
    enableBasicAutocompletion: true,
    fontSize: activeFontSize,
    fontFamily: activeFont,
    enableLiveAutocompletion: true,
    enableSnippets: true,
    selectionStyle: "line",
    cursorStyle: "smooth-slim",
    scrollPastEnd: false,
    tooltipFollowsMouse: true,
    newLineMode: 'windows',
    foldStyle: 'markbeginend',
    wrap: true,
    tabSize: 4
});


Editor.container.style.lineHeight = 1.8;
Editor.renderer.updateFontSize();


let startValue = "<";
startValue += "?php\n\n";
startValue += "//Start coding here\n\n";
Editor.setValue(startValue, 1);
Editor.focus();

// Make editor focus when code tab is clicked
$('#coder').click(() => {
    Editor.focus();
});


//Routes editor
let routesEditor = ace.edit("routesEditor", {
    theme: "ace/theme/" + activeTheme,
    mode: "ace/mode/php",
    enableEmmet: true,
    showPrintMargin: false,
    enableBasicAutocompletion: true,
    fontSize: activeFontSize,
    fontFamily: activeFont,
    enableLiveAutocompletion: true,
    enableSnippets: true,
    selectionStyle: "line",
    cursorStyle: "smooth-slim",
    scrollPastEnd: false,
    tooltipFollowsMouse: true,
    newLineMode: 'windows',
    foldStyle: 'markbeginend',
    wrap: true,
    tabSize: 4
});
routesEditor.container.style.lineHeight = 1.8;
routesEditor.renderer.updateFontSize();

/**
 * Get routes contents
 */
let routesHolder = $('#routeLoadingIndicator');
routesHolder.html(Spinner);

function getRouteCodes() {
    axios.post('/routes/contents')
        .then(response => {
            routesHolder.html('');
            routesEditor.setValue(response.data, 1);
            routesEditor.focus();
        }).catch(error => console.log(error));
}

$(document).ready(function () {
    getRouteCodes();
});

$('#router').click(function () {
    routesEditor.focus();
});

//Update routes
function updateRoutes() {
    blockModal('Please Wait');
    setTimeout(function () {
        let errorContents = routesEditor.getSession().getAnnotations();
        let response = errorContents[0];
        if (errorContents.length) {
            unblockModal();
            toastError(`
               ${response.text} on line <b>${response.row}</b>.
            `, 8000);
            routesEditor.focus();

            return false;
        }
        axios.post('routes/update', {contents: routesEditor.getSession().getValue()})
            .then(response => {
                let result = response.data;
                if (!result.updated) {
                    toastError("Failed to update routes.");
                }
                if (!result.same_contents) {
                    toast('Routes Updated');
                } else {
                    toastInfo('No Changes Made');
                }
                routesEditor.focus();
            }).catch(error => console.log(error.response));
    }, 1000);
}

const updateRoutesBtn = $('#updateRoutesBtn');
updateRoutesBtn.on('click', function (editor) {
    updateRoutes();
});


// Route editor shortcuts
routesEditor.commands.addCommand({
    name: 'updateRoutes',
    bindKey: {win: 'Ctrl-S', mac: 'Command-S'},
    exec: function (editor) {
        updateRoutes();
    },
    readOnly: true // false if this command should not apply in readOnly mode
});

/**
 *
 * @param message
 * @param delayTime
 * @param extra
 * @returns {*}
 */

function toast(message, delayTime = 3000, extra = '') {
    if (extra !== '') {
        extra = `<button class="btn-flat toast-action">${extra}</button>`;
    }
    let toastHTML = `<span><i class="material-icons left">check</i> ${message}</span>${extra}`;
    return M.toast({html: toastHTML, classes: 'pc-toast', displayLength: delayTime});
}

function toastInfo(message, delayTime = 3000, extra = '') {
    if (extra !== '') {
        extra = `<button class="btn-flat toast-action">${extra}</button>`;
    }
    let toastHTML = `<span><i class="material-icons left">info</i> ${message}</span>${extra}`;
    return M.toast({html: toastHTML, displayLength: delayTime});
}

function toastError(message, delayTime = 3000, extra = '') {
    if (extra !== '') {
        extra = `<button class="btn-flat toast-action">${extra}</button>`;
    }
    let toastHTML = `<span><i class="material-icons left">error_outline</i> ${message}</span>${extra}`;
    return M.toast({html: toastHTML, classes: 'pc-toast-error', displayLength: delayTime});
}

function blockModal(message) {
    let elem = document.querySelector('#blockModal');
    let instance = M.Modal.init(elem, {
        dismissible: false
    });
    instance.open();
    $('#blockModalContent').html(message);
}

function unblockModal() {
    $('#blockModal').modal('close');
}

function closeAlertBlockable(element = '') {
    $('#closeAlertBlockable').click(function (e) {
        if (element === '') {
            $.unblockUI();
        } else {
            $(element).unblock();
        }
        e.preventDefault();
    });
}

/**
 * Snippet vars
 */


let snippetNameInput = $('#snippetName');
let snippetsHolder = $('#snippetsHolder');
let snippetNameHolder = $('#snippetNameHolder');
let dynamicTitle = $('#dynamicTitle');
snippetNameHolder.hide();

/**
 * Add new snippet
 */

function initiateAddSnippet() {
    snippetsHolder.hide();
    snippetNameHolder.show();
    dynamicTitle.html('New Snippet');
    $('.add-snippet').hide('slow');
    snippetNameInput.focus();

    $('#cancelAddSnippet').click(function () {
        snippetsHolder.show();
        snippetNameHolder.hide();
        dynamicTitle.html('Your Snippets');
        $('#nameError').html('');
        $('.add-snippet').show();
    });
}

function resetSnippetModal() {
    snippetsHolder.show();
    snippetNameHolder.hide();
    dynamicTitle.html('Your Snippets');
    $('#nameError').html('');
    $('.add-snippet').show();
}

function addSnippet() {

    $('.add-snippet').click((e) => {
        initiateAddSnippet();
        e.preventDefault();
    });

    /**
     * Save snippet
     */

    snippetNameInput.on('input', function (e) {
        if ($(this).val() === '') {
            $('#nameError').html('Enter snippet name');
            return false;
        } else {
            $('#nameError').html('');
        }
    });

    $('#saveSnippet').click(function (e) {
        if (snippetNameInput.val() === '') {
            $('#nameError').html('Enter snippet name.');
            snippetNameInput.focus();
            return false;
        } else {
            $('#nameError').html('');
        }

        snippetsHolder.html('');

        saveSnippet();

        e.preventDefault();
    });
}

/**
 * Update snippet
 * @returns {boolean}
 */
function updateSnippet() {
    blockModal("Saving snippet");
    let codesInEditor = Editor.getSession().getValue();
    let activeSnippet = $('#activeSnippet').val();
    if (activeSnippet === '') {
        unblockModal();
        toastError("No snippet selected. Save this as new snippet rather.");
        Editor.focus();
        return false;
    }

    axios.post('/snippets/update', {id: activeSnippet, contents: codesInEditor})
        .then(response => {
            unblockModal();
            loadAllSnippets();
            toast("Snippet updated");
        })
        .catch(error => console.log(error))
}

/**
 * Store added snippet
 */
function saveSnippet() {
    let snippetSource = $('#snippetSource').val();
    let snippetContents = '<';
    if (snippetSource === 'blank' || snippetSource === null) {
        snippetContents += '?php\n\n';
        snippetContents += '//Start coding here';
    }

    if (snippetSource === 'editor') {
        snippetContents = Editor.getSession().getValue();
    }
    blockModal('Adding Snippet');
    axios.post('/snippets/add', {
        name: snippetNameInput.val(),
        contents: snippetContents
    }).then(response => {
        unblockModal();
        snippetNameInput.val('');
        let valueToSet = "";
        valueToSet += response.data.contents;
        snippetNameHolder.hide();
        snippetsHolder.show();
        dynamicTitle.html('Your Snippets');
        $('.add-snippet').show('slow');
        $('div').removeClass('code');
        snippetsHolder.prepend(`
            <div class="col m3">
            <a href="" class="load" id="${response.data.id}">
            <div class="card black snippet-card z-depth-2">
            <div class="card-image">
            <a href="" class="editSnippet snippet-action-btn btn-small halfway-fab blue-grey darken-3 btn-floating waves-effect waves-light" id="${response.data.id}" title="Edit"><i class="material-icons left">edit</i></a>
            <a href="" class="saveEditedSnippet hiddendiv snippet-action-btn btn-small halfway-fab purple darken-3 btn-floating waves-effect waves-light" id="${response.data.id}" title="Save"><i class="material-icons left">check</i></a>
            <a href="" class="deleteSnippet snippet-action-btn btn-small halfway-fab grey darken-3 btn-floating waves-effect waves-light" id="${response.data.id}" title="Delete"><i class="material-icons left">delete_forever</i></a>
            <a href="" class="cancelEditingSnippet hiddendiv snippet-action-btn btn-small halfway-fab red btn-floating waves-effect waves-light" id="${response.data.id}" title="Close"><i class="material-icons left">close</i></a>
            </div>
            <div class="card-content">
            <div class="code" id="new-code${response.data.id}" ace-mode="ace/mode/php" ace-theme="ace/theme/${AppSettings.theme}" ace-gutter="true" style="height: 130px; !important; display: block; max-height: 130px; min-height: 130px">
${valueToSet}
            </div>
            </div>
            <div class="card-action" id="snippetAction-${response.data.id}">
                <div class="truncate snippet-card-action-inner" id="${response.data.id}">
                <div class="input-field hiddendiv" id="${response.data.id}">
                <input type="text" name="edit_snippet_name" id="editSnippetName-${response.data.id}" class="snippetEditInput white-text" value="${response.data.name}">
                </div>
                <a href="#" class="load" id="${response.data.id}">${response.data.name}</a>
                </div>
            </div>
            </div>
            </a>
            </div>
            `);
        activateHighlighter();
        editSnippet();
        deleteSnippet();
        loadSnippetInEditor(response.data.id);

    }).catch(error => console.log(error.response));
}

function loadSnippetInEditor(id, notFocus = false) {
    let tabList = $('#phpcTabs');
    let tabsWithCodes = $('#tabsWithCodes');
    blockModal('Loading snippet');
    axios.post('/snippets/load-in-editor', {snippetId: id})
        .then(response => {
            unblockModal();
            $('#activeSnippet').val(id);
            $('#snippetsModal').modal('close');
            let snippet = '';
            if (response.data.snippet === '') {
                snippet += '\n/** Start coding here */\n';
            } else {
                snippet += response.data.snippet + '\n';
            }

            Editor.setValue(snippet, 1);
            if (!notFocus) {
                Editor.focus();
            }
        })
        .catch(error => console.log(error.response));
}

function getSnippetsLayouts(response) {
    let newSnippets = '';
    if (response.data.all_snippets.length > 0) {
        response.data.all_snippets.forEach(function (snippet) {
            newSnippets += `
               <div class="col m3">
        <div class="card black z-depth-2 snippet-card">
        <div class="card-image">
        <a href="" class="editSnippet snippet-action-btn btn-small halfway-fab blue-grey darken-3 btn-floating waves-effect waves-light" id="${snippet.id}" title="Edit"><i class="material-icons left">edit</i></a>
        <a href="" class="saveEditedSnippet hiddendiv snippet-action-btn btn-small halfway-fab purple darken-3 btn-floating waves-effect waves-light" id="${snippet.id}" title="Save"><i class="material-icons left">check</i></a>
        <a href="" class="deleteSnippet snippet-action-btn btn-small halfway-fab grey darken-3 btn-floating waves-effect waves-light" id="${snippet.id}" title="Delete"><i class="material-icons left">delete_forever</i></a>
        <a href="" class="cancelEditingSnippet hiddendiv snippet-action-btn btn-small halfway-fab red btn-floating waves-effect waves-light" id="${snippet.id}" title="Close"><i class="material-icons left">close</i></a>
        </div>
         <a href="" class="load" id="${snippet.id}">
            <div class="card-content">
                <div class="code" id="snippetCode" ace-mode="ace/mode/php" ace-theme="ace/theme/${AppSettings.theme}" 
                ace-gutter="true">
${snippet.contents}
                </div>
        </div>
        </a>
        <div class="card-action" id="snippetAction-${snippet.id}">
            <div class="truncate snippet-card-action-inner" id="${snippet.id}">
            <div class="input-field hiddendiv" id="${snippet.id}">
            <input type="text" name="edit_snippet_name" id="editSnippetName-${snippet.id}" class="snippetEditInput white-text" value="${snippet.name}">
            </div>
            <a href="#" class="load" id="${snippet.id}">${snippet.name}</a>
            </div>
        </div>
    </div>
</div>   
               `;
        });
        snippetsHolder.html(newSnippets);
        activateHighlighter();
        editSnippet();
        deleteSnippet();
        $('.load').click(function (e) {
            loadSnippetInEditor(this.id);
            e.preventDefault();
        });

    } else {
        snippetsHolder.html(`<div class="col m6 offset-m3">
                        <div class="card grey darken-4" id="noSnippetCard">
                            <div class="card-content center-align">
                                <h6>No snippets found</h6><br/><br/>
                                <a href="" class="btn purple darken-2 add-snippet" id="addSnippet"><i class="material-icons left">add</i> Create Now</a>
                            </div>
                        </div>
                    </div>
                `);

        addSnippet();
    }
}

function loadAllSnippets() {
    axios.post('/snippets/show')
        .then(response => {
            getSnippetsLayouts(response);
        })
        .catch(error => {

        });
}

//Snippets highlighter
let highlight = ace.require("ace/ext/static_highlight");
let dom = ace.require("ace/lib/dom");

function qsa(sel) {
    return Array.apply(null, document.querySelectorAll(sel));
}

function activateHighlighter() {
    qsa(".code").forEach(function (codeEl) {
        highlight(codeEl, {
            mode: codeEl.getAttribute("ace-mode"),
            theme: codeEl.getAttribute("ace-theme"),
            startLineNumber: 1,
            showGutter: codeEl.getAttribute("ace-gutter"),
            trim: true,
            wrap: false,
            fontSize: 14,
            fontFamily: 'Consolas',
        }, function (highlighted) {

        });
    });
}

function getLastSnippet() {
    axios.post('/get-codes')
        .then(response => {
            unblockModal();
            if (response.data !== 'not-allowed') {
                let codes = '';
                if (response.data.codes === '') {
                    codes += '<';
                    codes += '?php';
                    codes += '\n\n//Start coding here\n';
                } else {
                    codes += response.data.codes + '\n';
                }
                Editor.setValue(codes, 1);
                Editor.focus();
                $('#activeSnippet').val(response.data.id)
            } else {
                toastError("Loading last codes disabled.");
            }
        });
}

function editSnippet() {
    $('.editSnippet').click(function (e) {
        let snippetId = this.id;
        let This = $(this);
        let cardAction = $('.card-action#snippetAction-' + snippetId);
        let cardBtnHolder = $('.card-image');
        let actionInput = cardAction.find('input#editSnippetName-' + snippetId);
        let actionInputField = cardAction.find('div.input-field#' + snippetId);
        let snippetNameWrapper = cardAction.find(`a.load#${snippetId}`);
        let saveBtn = cardBtnHolder.find('a.saveEditedSnippet#' + snippetId);
        let closeBtn = cardBtnHolder.find('a.cancelEditingSnippet#' + snippetId);
        let deleteBtn = cardBtnHolder.find('a.deleteSnippet#' + snippetId);

        cardAction.find('.snippet-card-action-inner').addClass('active');
        cardAction.addClass('has-input');
        saveBtn.removeClass('hiddendiv');
        This.hide();
        deleteBtn.hide();
        closeBtn.removeClass('hiddendiv');
        actionInputField.removeClass('hiddendiv');
        snippetNameWrapper.hide();
        actionInput.setCursorToTextEnd();

        function save() {
            axios.post('/snippets/update-name', {id: snippetId, name: actionInput.val()})
                .then(response => {
                    let snippet = response.data;
                    snippetNameWrapper.html(snippet.name);
                    unblockModal();
                })
                .catch(error => console.log(error.response));
        }

        // keyboardJS.bind('enter', function (e) {
        //     blockModal('Saving');
        //     actionInput.setCursorToTextEnd();
        //     save();
        // });

        /**
         * Save edited snippet name
         */
        saveBtn.click(function (e) {
            blockModal('Saving');
            actionInput.setCursorToTextEnd();

            save();
            e.preventDefault();
        });

        /**
         * Close snippet editing
         */

        function closeEditing() {
            cardAction.find('.snippet-card-action-inner').removeClass('active');
            cardAction.removeClass('has-input');
            saveBtn.addClass('hiddendiv');
            This.show();
            deleteBtn.show();
            closeBtn.addClass('hiddendiv');
            actionInputField.addClass('hiddendiv');
            snippetNameWrapper.show();
        }

        keyboardJS.bind('ctrl + q', function (e) {
            closeEditing();
        });

        closeBtn.click(function (e) {
            closeEditing();
            e.preventDefault();
        });

        e.preventDefault();
    });
}

function deleteSnippet() {
    let deleteBtn = $('.deleteSnippet');
    deleteBtn.click(function (e) {
        let snippetId = this.id;
        let alertDialog = $('#alertBlockable');
        let element = $('#snippetsModal');
        element.block({message: alertDialog});

        let yesDelete = $('.yesDelete');
        yesDelete.attr('id', snippetId);

        yesDelete.click(function (e) {
            let id = yesDelete.attr('id');
            blockModal('Deleting');
            axios.post('/snippets/delete', {id: id})
                .then(response => {
                    let activeSnippet = $('#activeSnippet');
                    if (response.data.id.toString() === activeSnippet.val()) {
                        activeSnippet.val('');
                        let snippet = '<';
                        snippet += '?php\n\n';
                        snippet += '//Start coding here\n\n';
                        Editor.setValue(snippet, 1);
                        Editor.focus();
                    }

                    element.unblock();
                    unblockModal();
                    loadAllSnippets();
                })
                .catch(error => console.log(error.response));
            e.preventDefault();
        });

        keyboardJS.bind('ctrl + q', function (e) {
            $('#snippetsModal').unblock();
        });

        closeAlertBlockable('#snippetsModal');

        e.preventDefault();
    });
}

deleteSnippet();
activateHighlighter();

function updateSnippetsHighlighter() {
    axios.post('/settings/get-dynamic')
        .then(({data}) => {
            if ($('body').hasClass('light-theme')) {
                AppSettings.theme = 'chrome';
            } else {
                AppSettings.theme = 'chaos';
            }
            loadAllSnippets();
        }).catch(({error}) => console.log(error));
}

function updateEditorsFontSize(fontSize) {
    let enteredSize = Number(fontSize);
    let contentsHolder = $('.content-inner');
    contentsHolder.block({message: ''});
    if (isNaN(enteredSize)) {
        toastError('Invalid size enterd. Only numbers allowed');
        contentsHolder.unblock();
        return false;
    }

    function updateFontSize() {
        setTimeout(() => {
            contentsHolder.unblock();
            Editor.setFontSize(enteredSize);
            routesEditor.setFontSize(enteredSize);
        }, 100);
    }
    updateFontSize();
    keyboardJS.bind('ctrl + s', function (e) {
        updateFontSize();
    });
    keyboardJS.bind('ctrl + enter', function (e) {
        updateFontSize();
    });

    axios.post('/settings/update-font-size', {fontSize: enteredSize})
        .then(({data}) => {}).catch(({error}) => console.log(error));
}

let previewHolder = $('#codesPreview');
let message = 'Please wait..';
let preLoader = `
        <div style="margin-top: 20%; display: block; text-align: center;">
            <div class="preloader-wrapper small animated fadeIn active">
                <div class="spinner-layer spinner-blue-only">
                  <div class="circle-clipper left">
                    <div class="circle"></div>
                  </div><div class="gap-patch">
                    <div class="circle"></div>
                  </div><div class="circle-clipper right">
                    <div class="circle"></div>
                  </div>
                </div>
            </div>
            <div style="margin-left: 10px; text-align: center" id="message">Loading your preview...</div>
        </div>
    `;

previewHolder.html(preLoader);

Editor.getSession().on('change', function () {

});


//Show preview
$('#showPreview').click(function (e) {

    let contents = Editor.getSession().getValue();
    previewHolder.removeClass('has-errors');
    previewHolder.html(preLoader);

    if (startValue === contents) {
        return previewHolder.html("You've not started coding yet!").addClass('has-errors');
    }

    axios.post('/get-preview', {contents: contents})
        .then(response => {
            let result = response.data;
            if (typeof result === 'object') {
                previewHolder.addClass('has-errors');
                return previewHolder.html(`${result.message}`);
            } else {
                previewHolder.removeClass('has-errors');
            }
            previewHolder.html(`${result}`);
        })
        .catch(error => {
            console.log(error);
        });

    e.preventDefault();
});

function postPoneUpdateChecker() {
    let alertsHolder = $('#alertsHolder');
    axios.post('/settings/postpone-update-check')
        .then(({data}) => {
            alertsHolder.addClass('animated zoomOut hide').find('#alertsHolderContent').html('');
            toast(data.message, 10000);
        }).catch(({error}) => console.log(error));
}

function doAutomaticUpdatesChecker() {
    let alertsHolder = $('#alertsHolder');
    axios.post('/auto-update-checker')
        .then(({data}) => {
            if (!data.uptoDate && data.hasError === false) {
                alertsHolder.removeClass('hide').addClass('animated zoomIn')
                    .find('#alertsHolderContent').html(`
                    <h4>New version  available</h4>
                    <p>PHP Console version ${data.versions.available}, is available. Update now and get better experience.</p>
                    `);
                alertsHolder.find('#alertsHolderAction').html(`
                    <a href="/download-updates" target="_blank" class="btn purple darken-4">Update Now</a>
                    <button type="button" class="btn red darken-3" id="postponeUpdates"><i class="material-icons left">close</i> Close</button>
                `);

            } if (data.hasError && !data.timeNotReached) {
                alertsHolder.removeClass('hide').addClass('animated zoomIn')
                    .find('#alertsHolderContent').html(`
                        <h4 class="red-text">Updates Failed</h4>
                        <p class="red-text text-lighten-3">We couldn't get updates for your now. <b>Reason:</b> <i class="red-text">${data.errorMessage}</i>.</p>
                    `);
                alertsHolder.find('#alertsHolderAction').html(`
                    <button type="button" class="btn red darken-3" id="postponeUpdates"><i class="material-icons left">close</i> Close</button>
                `);
            }

            let postPoneUpdatesBtn = alertsHolder.find('#alertsHolderAction button#postponeUpdates');
            postPoneUpdatesBtn.click(function () {
                postPoneUpdateChecker();
                return false;
            });
            //console.log(data)
        }).catch(({error}) => console.log(error));
}


(function ($) {
    $(window).on('load', function () {
        /**
         * Check if user is updating app
         */
        axios.post('/updates-active')
            .then(response => {
                let status = response.data.status;
                if (status === true) {
                    blockModal(`
                        <h4>Upgrading App</h4>
                        <p>We are still working on your updates</p>
                        <br/>
                    `);
                }
            }).catch(error => console.log(error.response));

    })
})(jQuery);

setInterval(function () {
    /**
     * Check if user is updating app
     */
    axios.post('/updates-active')
        .then(response => {
            let status = response.data.status;
            if (status === true) {
                blockModal(`
                <h4>Upgrading App</h4>
                <p>We are still working on your updates</p>
                <br/>
                `);
            } else {
                unblockModal();
            }
        }).catch(error => console.log(error.response));
}, 3000);

/**
 * Search Snippets
 */

let searchInput = $('#snippetsSearch');
/**
 * Color scheme, shortcodes and Saving last codes
 */
$(document).ready(function () {
    const themeType = $('#themeType');
    const changeTheme = $('#changeTheme');

    themeType.change(() => {
        blockModal("Activating...");
        let selectedValue = themeType.val();

        /**
         * Change theme type (dark or light)
         */
        if (selectedValue != '') {
            unblockModal();
            $('body').removeClass('light-theme dark-theme').addClass(selectedValue);

            updateSnippetsHighlighter();
            Editor.focus();
            routesEditor.focus();
        }
        axios.post('/change-theme-type', {type: selectedValue})
            .then(response => {})
            .catch(error => console.log(error));
    });

    /**
     * Change editor color scheme theme.
     */
    changeTheme.change(() => {
        blockModal("Please wait...");
        let selectedValue = changeTheme.val();
        if (selectedValue != '') {
            unblockModal();
            Editor.setTheme("ace/theme/" + selectedValue);
            routesEditor.setTheme("ace/theme/" + selectedValue);

            Editor.focus();
            //Update snippets highlighter
            axios.post('/settings/get-dynamic')
                .then(({data}) => {
                    if ($('body').hasClass('dark-theme')) {
                        AppSettings.theme = 'chaos';
                    } else {
                        AppSettings.theme = data.theme;
                    }
                    $('#snippetsHolder').attr('data-theme', data.theme_type);
                    loadAllSnippets();
                }).catch(({error}) => console.log(error));
        }

        axios.post('/change-theme', {theme: selectedValue})
            .then(response => {})
            .catch(error => console.log(error));
    });

    const keepLastCodes = $('#keepLastCodes');
    keepLastCodes.click(() => {
        blockModal("Saving...");
        let checkedValue = 'no';
        if (keepLastCodes.is(':checked')) {
            checkedValue = 'yes';
        }

        axios.post('/keep-last-codes', {keep_codes: checkedValue})
            .then(response => {
                unblockModal();
                let codes = '';

                if (response.data.status === 'yes') {
                    codes += response.data.contents;
                    $('#activeSnippet').val(response.data.snippet.id);
                    toast("Load last codes enabled.");
                } else {
                    codes += '<';
                    codes += '?php';
                    codes += "\n\n//Start coding here\n";
                    toast("Load last codes disabled.");
                }

                Editor.setValue(codes + '\n', 1);
                Editor.focus();
            })
            .catch(error => console.log(error))
    });

    //Recursively check if keeping last codes in memory
    $('.updateSnippet').on('click', function () {
        updateSnippet();
    });

    blockModal("Initializing...");

    //Get last snippet on editor
    getLastSnippet();

    //Force editor to be focus
    Editor.focus();

    //Key Bindings
    //Open snippets modal
    keyboardJS.bind('ctrl + o', function (e) {
        resetSnippetModal();
        $('#snippetsModal').modal('open');
    });
    //Add snippet command
    keyboardJS.bind('ctrl + n', function (e) {
        $('#snippetsModal').modal('open');
        initiateAddSnippet();
    });
    //SHow help modal
    keyboardJS.bind('alt + h', function (e) {
        $('#helpModal').modal('open');
    });
    Editor.commands.addCommand({
        name: 'showHelpCommand',
        bindKey: {win: 'Alt-H', mac: 'Alt-H'},
        exec: function (editor) {
            $('#helpModal').modal('open');
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });

    //Save current editor codes to memory
    keyboardJS.bind('ctrl + enter', function (e) {
        updateSnippet();
    });
    // keyboardJS.bind('ctrl + s', function (e) {
    //     updateSnippet();
    // });
    Editor.commands.addCommand({
        name: 'saveCodesCommand',
        bindKey: {win: 'Ctrl-ENTER', mac: 'Command-ENTER'},
        exec: function (editor) {
            updateSnippet();
        },
        readOnly: false // false if this command should not apply in readOnly mode
    });

    Editor.commands.addCommand({
        name: 'saveCodesCommand',
        bindKey: {win: 'Ctrl-S', mac: 'Command-S'},
        exec: function (editor) {
            updateSnippet();
        },
        readOnly: false // false if this command should not apply in readOnly mode
    });

    //Show keyboard shortcuts
    keyboardJS.bind('ctrl + k', function (e) {
        $('#shortcodesModal').modal('open');
    });
    Editor.commands.addCommand({
        name: 'showShortcutsCommand',
        bindKey: {win: 'Ctrl-K', mac: 'command-K'},
        exec: function (editor) {
            $('#shortcodesModal').modal('open');
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });

    //Refresh
    keyboardJS.bind('ctrl + r', function (e) {
        window.location.reload();
    });

    keyboardJS.bind('home', function (e) {
        window.location.href = '/';
    });

    /** New Snippet */

    /**
     * Add snippet
     */
    addSnippet();

    /**
     * Load all snippets
     */
    loadAllSnippets();

    /**
     * Load snippets on modal open
     */

    $('#openSnippetsModal').click(function () {
        loadAllSnippets();
    });

    /**
     * Update snippets highlight
     */
    updateSnippetsHighlighter();

    /** Fonts */
    const systemFonts = $('#systemFont');
    const systemFontSize = $('#systemFontSize');
    let contentsHolder = $('.content-inner');
    systemFonts.change(function () {
        //contentsHolder.block({message: 'Wait...'});
        let selectedFont = $(this).val();
        if (selectedFont != '') {
            //contentsHolder.unblock();
            Editor.setOption('fontFamily', selectedFont);
            routesEditor.setOption('fontFamily', selectedFont);
            Editor.focus();
            routesEditor.focus();
        }

        axios.post('/settings/update-font', {font: selectedFont})
            .then(({data}) => {}).catch(({error}) => console.log(error));
    });

    systemFontSize.on('input', function (e) {
        //contentsHolder.block({message: 'Setting font size...'});
        updateEditorsFontSize(systemFontSize.val());
    });

    // Let's autocheck updates after each minute
    doAutomaticUpdatesChecker();
    setInterval(function () {
        doAutomaticUpdatesChecker();
    }, 10000);

    // Update timezones
    const timeZone = $('#timeZone');
    timeZone.on('change', function () {
        axios.post('/settings/update-timezone', {timezone: timeZone.val()})
            .then(({data}) => {
                toast(`Timezone changed`);
            }).catch(({error}) => console.log(error));
    });

});


//Snippets search
searchInput.on('input', function () {
    let searchValue = searchInput.val();
    axios.post('/snippets/show', {name: searchValue})
        .then(response => {
            getSnippetsLayouts(response);
        }).catch(error => console.log(error));
});







