<div id="snippetsModal" class="modal bottom-sheet modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <div class="col s12">
                <div class="modal-title"><h4 class="left" id="dynamicTitle">Your Snippets</h4>
                    <a href="" class="btn purple darken-2 add-snippet right btn-rounded" id="addSnippet"><i class="material-icons left">add</i> New Snippet</a>
                </div>
            </div>
        </div>
        <div class="row" style="padding-right: 20px">
            <div class="col s6 offset-s6 m4 offset-m8" style=" margin-top: -20px;">
                <div class="">
                    <div class="input-field snippets-search">
                        <input type="text" name="" id="snippetsSearch" class="input">
                        <label for="" style="margin-left: 20px;">Search snippets...</label>
                    </div>
                </div>
            </div>
            <div class="col s12" id="snippetNameHolder">
                <div class="row">
                    <div class="col m6">
                        <div class="card">
                            <div class="card-content">
                                <div class="input-field">
                                    <input type="text" class="" name="snippet-name" id="snippetName">
                                    <label for="">Snippet Name</label>
                                    <div id="nameError" class="red-text"></div>
                                </div>
                            </div>
                            <div class="card-action right-align">
                                <button type="button" id="saveSnippet" class="btn purple"><i class="material-icons left">check</i> Save Snippet</button>
                            </div>
                        </div>
                    </div>
                    <div class="col m6">
                        <div class="card">
                            <div class="card-content">
                                <p>Choose either to create a blank snippet or create from the codes currenlty in editor.</p>
                                <div class="row" style="margin-bottom: -12px;">
                                    <div class="col m6">
                                        <div class="input-field">
                                            <select class="select" id="snippetSource" name="snippet_source">
                                                <option value="" disabled selected>Choose snippet source...</option>
                                                <option value="blank">Blank Snippet</option>
                                                <option value="editor">Editor Codes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action right-align">
                                <button type="button" id="cancelAddSnippet" class="btn white purple-text"><i class="material-icons left">close</i> Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <div class="row" id="snippetsHolder">

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="" class="btn purple darken-2 add-snippet hide" id="addSnippet"><i class="material-icons left">add</i> New Snippet</a>
        <a href="#!" class="modal-action modal-close red waves-effect waves-light white-text btn" id="closeSnippetModal"><i class="material-icons left">close</i> Close</a>
    </div>
</div>