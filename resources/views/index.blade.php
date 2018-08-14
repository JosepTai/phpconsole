@include('inc.header')

<body class="{{ settings(true)->theme_type }} {{ settings(true)->theme }}">
<div id="app">
    <Navbar></Navbar>
    <div class="row">
        <div class="col s3">
            <div class="sidebar-wrap">
                <div class="sidebar-inner" data-simplebar data-simplebar-auto-hide="false">
                    <div class="center-align" style="margin-bottom: 0;">
                        {{--<div class="profile">--}}
                        {{--<img src="{{ asset('img/logo-white.png') }}" alt="Logo" class="app-logo"/>--}}
                        {{--</div>--}}
                    </div>
                    <div class="input-field col s12">
                        <br/>
                        <select class="select input-rounded" id="themeType" name="theme_type">
                            <option value="" disabled selected>Change theme</option>
                            <option value="dark-theme" @if(settings(true)->theme_type == 'dark-theme') selected @endif>Dark theme</option>
                            <option value="light-theme" @if(settings(true)->theme_type == 'light-theme') selected @endif>Light theme</option>
                        </select>
                        <label for="">Theme</label>
                    </div>

                    <div class="input-field col s12">
                        <br/>
                        <select class="select" id="changeTheme" name="theme">
                            <option value="" disabled selected>Select scheme</option>
                            <optgroup label="Dark Themes">
                                @foreach($themes->dark as $theme)
                                    <option value="{{ $theme->real_name }}" @if(settings(true)->theme == $theme->real_name) selected @endif>{{ $theme->display_name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Light Themes">
                                @foreach($themes->light as $theme)
                                    <option value="{{ $theme->real_name }}" @if(settings(true)->theme == $theme->real_name) selected @endif>{{ $theme->display_name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <label for="">Editor Color Scheme</label>
                    </div>
                    <div class="col s12" id="fontsHolder">
                        <p style="margin-top: -5px; color: #999999;" class="small">App Fonts & Size</p>
                        <div class="row" style="margin-top: -10px">
                            <div class="col m9">
                                <div class="input-field">
                                    <select class="select input-rounded" id="systemFont" name="system_font">
                                        <option value="" disabled selected>Select font</option>
                                        @foreach($fonts as $font)
                                            <option value="{{ $font->real_name }}"
                                                    @if($font->real_name == settings(true)->app_font_family) selected @endif >{{ $font->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col m3">
                                <div class="input-field">
                                    <input type="text" class="input input-rounded" id="systemFontSize"
                                           value="{{ settings(true)->app_font_size }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-field col s12 remove-default-select" id="timeZonesSelect" style="margin-top: -10px">
                        <br/>
                        <select class="select select2" id="timeZone" name="timezone" style="width: 100%">
                            <option value="" disabled selected>Timezone</option>
                            @foreach($timezones as $timezone)
                                <optgroup label="{{ $timezone->text }}" class="truncate">
                                    @foreach($timezone->utc as $zone)
                                        <option value="{{ $zone }}" @if(settings(true)->timezone == $zone) selected @endif class="truncate">{{ str_replace('/', ' ', str_replace('_', ' ', $zone)) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <label for="">Timezones</label>
                    </div>

                    <div class="input-field col s12" style="margin-top: -5px;">
                        <p>LOAD LAST CODES</p>
                        <div class="switch">
                            <label>
                                No
                                <input type="checkbox" value="yes" id="keepLastCodes"
                                       @if(settings(true)->keep_last_codes == 'yes') checked @endif >
                                <span class="lever"></span>
                                Yes
                            </label>
                        </div>
                    </div>
                    <div class="input-field col s12">
                        <a href="{{ url('console') }}" target="_blank" class="btn btn-rounded purple darken-3">
                            <i class="material-icons left">code</i> Terminal
                        </a>
                    </div>
                    <Sidebar></Sidebar>
                </div>
            </div>
        </div>
        <div class="col s9">
            <div class="content-wrap">
                {{--<textarea v-bind="contents" id="contents" name="editor" rows="1" cols="1"></textarea>--}}
                <ul id="phpcTabs" class="tabs tabs-fixed-width blue-grey darken-4">
                    <li class="tab col s6"></li>
                    <li class="tab col s2"><a href="#routes" class="" id="router">Routes</a></li>
                    <li class="tab col s2"><a href="#codes" class="active" id="coder">Code</a></li>
                    <li class="tab col s2"><a href="#preview" id="showPreview" class="">Execute</a></li>
                </ul>
                <div id="tabsWithCodes">
                    <div id="routes">
                        <div id="routeLoadingIndicator"></div>
                        <div class="content-inner animated fadeIn white" id="routesEditor">
                            [..Click Here or Press ENTER Key..]
                        </div>
                        <button type="button" class="btn btn-rounded purple darken-3" id="updateRoutesBtn"
                                style="position:absolute; bottom: 110px; right: 10px;">Save Routes
                        </button>
                    </div>
                    <div id="codes">
                        <div class="content-codes">
                            <div class="content-inner z-depth- animated fadeIn" id="editor"></div>
                        </div>
                        <div class="fixed-action-btn">
                            <a class="btn-floating waves-effect waves-light updateSnippet saveCodes btn-large red">
                                <i class="large material-icons">save</i>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="preview">
                    <div class="content-inner z-depth-2 white black-text" style="z-index: 2;">
                        <div id="codesPreview" style="padding: 20px;" class="animated fadeIn"></div>
                    </div>

                </div>
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
<div id="blockModal" class="modal" style="width: 25% !important;">
    <div class="modal-content center-align">
        <div class="preloader-wrapper active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <div id="blockModalContent"></div>
    </div>
</div>
@include('modals')
<div class="open-snippets" style="right: 100px; bottom: 25px; position:absolute;">

</div>
<div id="alertsHolder" class="alerts-holder hide"
     style="width: 40%; position: absolute; z-index: 99999; bottom: 0; right: 50px;">
    <div class="card alerts-holder-card z-depth-4" id="alertsHolderCard">
        <div class="card-content" id="alertsHolderContent">
            Hello
        </div>
        <div class="card-action right-align" id="alertsHolderAction">

        </div>
    </div>
</div>
<input type="hidden" name="active_snippet" value="" id="activeSnippet">


@include('inc.footer')