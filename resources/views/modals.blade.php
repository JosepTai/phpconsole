<!-- Modal Structure -->
<div id="shortcodesModal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>Keyboard Shortcodes</h4>
        <p>
            showSettingsMenu : <span class="btn black">Ctrl-</span><br/>
            goToNextError : <span class="btn black">Alt-E</span><br/>
            goToPreviousError : <span class="btn black">Alt-Shift-E</span><br/>
            selectall : <span class="btn black">Ctrl-A</span><br/>
            gotoline : <span class="btn black">Ctrl-L</span><br/>
            fold : <span class="btn black">Alt-L|Ctrl-F1</span><br/>
            unfold : <span class="btn black">Alt-Shift-L|Ctrl-Shift-F1</span><br/>
            <!--toggleFoldWidget : F2-->
            <!--toggleParentFoldWidget : Alt-F2-->
            <!--foldOther : Alt-0-->
            <!--unfoldall : Alt-Shift-0-->
            <!--findnext : Ctrl-K-->
            <!--findprevious : Ctrl-Shift-K-->
            <!--selectOrFindNext : Alt-K-->
            <!--selectOrFindPrevious : Alt-Shift-K-->
            find : <span class="btn black">Ctrl-F</span><br/>
            <!--overwrite : Insert-->
            <!--selecttostart : Ctrl-Shift-Home-->
            <!--gotostart : Ctrl-Home-->
            <!--selectup : Shift-Up-->
            <!--golineup : Up-->
            <!--selecttoend : Ctrl-Shift-End-->
            <!--gotoend : Ctrl-End-->
            <!--selectdown : Shift-Down-->
            <!--golinedown : Down-->
            <!--selectwordleft : Ctrl-Shift-Left-->
            <!--gotowordleft : Ctrl-Left-->
            <!--selecttolinestart : Alt-Shift-Left-->
            <!--gotolinestart : Alt-Left|Home-->
            <!--selectleft : Shift-Left-->
            <!--gotoleft : Left-->
            <!--selectwordright : Ctrl-Shift-Right-->
            <!--gotowordright : Ctrl-Right-->
            <!--selecttolineend : Alt-Shift-Right-->
            <!--gotolineend : Alt-Right|End-->
            <!--selectright : Shift-Right-->
            <!--gotoright : Right-->
            <!--selectpagedown : Shift-Pagedown-->
            <!--gotopagedown : Pagedown-->
            <!--selectpageup : Shift-Pageup-->
            <!--gotopageup : Pageup-->
            <!--scrollup : Ctrl-Up-->
            <!--scrolldown : Ctrl-Down-->
            <!--selectlinestart : Shift-Home-->
            <!--selectlineend : Shift-End-->
            <!--togglerecording : Ctrl-Alt-E-->
            <!--replaymacro : Ctrl-Shift-E-->
            <!--jumptomatching : Ctrl-P-->
            <!--selecttomatching : Ctrl-Shift-P-->
            <!--expandToMatching : Ctrl-Shift-M-->
            <!--removeline : Ctrl-D-->
            <!--duplicateSelection : Ctrl-Shift-D-->
            <!--sortlines : Ctrl-Alt-S-->
            <!--togglecomment : Ctrl-/-->
            <!--toggleBlockComment : Ctrl-Shift-/-->
            <!--modifyNumberUp : Ctrl-Shift-Up-->
            <!--modifyNumberDown : Ctrl-Shift-Down-->
            replace : <span class="btn black">Ctrl-H</span><br/>
            <!--undo : Ctrl-Z-->
            <!--redo : Ctrl-Shift-Z|Ctrl-Y-->
            <!--copylinesup : Alt-Shift-Up-->
            <!--movelinesup : Alt-Up-->
            <!--copylinesdown : Alt-Shift-Down-->
            <!--movelinesdown : Alt-Down-->
            <!--del : Delete-->
            <!--backspace : Shift-Backspace|Backspace-->
            <!--cut_or_delete : Shift-Delete-->
            <!--removetolinestart : Alt-Backspace-->
            <!--removetolineend : Alt-Delete-->
            <!--removetolinestarthard : Ctrl-Shift-Backspace-->
            <!--removetolineendhard : Ctrl-Shift-Delete-->
            <!--removewordleft : Ctrl-Backspace-->
            <!--removewordright : Ctrl-Delete-->
            <!--outdent : Shift-Tab-->
            <!--indent : Tab-->
            <!--blockoutdent : Ctrl-[-->
            <!--blockindent : Ctrl-]-->
            <!--transposeletters : Alt-Shift-X-->
            <!--touppercase : Ctrl-U-->
            <!--tolowercase : Ctrl-Shift-U-->
            <!--expandtoline : Ctrl-Shift-L-->
            <!--addCursorAbove : Ctrl-Alt-Up-->
            <!--addCursorBelow : Ctrl-Alt-Down-->
            <!--addCursorAboveSkipCurrent : Ctrl-Alt-Shift-Up-->
            <!--addCursorBelowSkipCurrent : Ctrl-Alt-Shift-Down-->
            <!--selectMoreBefore : Ctrl-Alt-Left-->
            <!--selectMoreAfter : Ctrl-Alt-Right-->
            <!--selectNextBefore : Ctrl-Alt-Shift-Left-->
            <!--selectNextAfter : Ctrl-Alt-Shift-Right-->
            <!--splitIntoLines : Ctrl-Alt-L-->
            <!--alignCursors : Ctrl-Alt-A-->
            findAll : <span class="btn black">Ctrl-Alt-K</span><br/>
            showKeyboardShortcuts : <span class="btn black">Ctrl-Alt-H</span>
        </p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>

{{--Help Modal--}}

<div id="helpModal" class="modal">
    <div class="modal-content">
        <div class="col">
            <table class="table table-striped">
                <tr>
                    <th>Email:</th>
                    <td>josiahoyahaya@gmail.com</td>
                </tr>
                <tr>
                    <th>Social Networks:</th>
                    <td>@josiahoyahaya</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
    </div>
</div>

<div id="alertBlockable" class="card" style="display: none; cursor:default; margin-bottom: -20px;">
    <div class="card-content">
        <h4>Are you sure?</h4>
    </div>
    <div class="card-action">
        <button type="button" class="red btn waves-effect waves-light yesDelete" id=""><i class="material-icons left">delete_forever</i>Yes</button>
        <button type="button" class="btn grey darken-2 waves-effect waves-light" id="closeAlertBlockable"><i class="material-icons left">close</i> No</button>
    </div>
</div>

@includeIf('modals.snippets')