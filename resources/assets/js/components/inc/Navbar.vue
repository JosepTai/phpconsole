<template>
    <div>
        <ul id="dropdown1" class="dropdown-content">
            <li><a href="#shortcodesModal" class="modal-trigger"><i class="material-icons left">code</i> Shortcodes</a></li>
            <li><a href="#helpModal" class="modal-trigger"><i class="material-icons left">help</i> Help</a></li>
            <li class="divider"></li>
            <li><a href="https://github.com/coderatio/phpconsole" target="_blank"><i class="material-icons left">exit_to_app</i> Contribute</a></li>
        </ul>
        <nav>
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo left"><span class="app-name"><span class="lang-name">PHP</span> Console </span>v{{ AppSettings.version }}</a>
                <ul class="right hide-on-small-only">
                    <!--<router-link to="/code" tag="li">-->
                        <!--<a><i class="material-icons left">code</i> Code</a>-->
                    <!--</router-link>-->
                    <!--<router-link to="/preview" tag="li">-->
                        <!--<a><i class="material-icons left">visibility</i> Preview</a>-->
                    <!--</router-link>-->
                    <li class="special-list">
                        <a href="#snippetsModal" class="btn modal-trigger white-text purple darken-2 btn-rounded waves-effect waves-light" id="openSnippetsModal">
                        <i class="large material-icons left">dashboard</i> Snippets
                    </a>
                    </li>
                    <li class="special-list">
                        <a href="/check-updates" class="btn purple white-text darken-2" id="navAppName" target="_blank">App Update</a>
                    </li>
                    <li>
                        <a href="" class="dropdown-trigger" data-target="dropdown1"><i class="material-icons">more_vert</i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "Navbar",
        data() {
            return {
                AppSettings: AppSettings
            }
        }
    }
</script>

<style scoped>
    .brand-logo {
        margin-left: 25px;
        font-size: 14px;
        font-weight: normal;
        color: #666;
    }
    #helpModal.modal {
        width: 35% !important;
    }
    .dark-theme .app-name {
        color: #b9c7de;
    }
    .light-theme .app-name {
        color: #555b66;
    }
    .app-name {
        font-size: 20px;
    }
    .app-name .lang-name {
        font-style: italic;
        font-weight: bolder;
    }
</style>