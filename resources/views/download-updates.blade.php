@include('inc.header')
<body class="{{ settings(true)->theme_type }} {{ settings(true)->theme }}">
<div id="app">
    <div class="container">
        <div class="row">

            <div class="col l6 offset-l3 m8 offset-m2" style="margin-top: 20px">
                <div class="dymanic-card">
                    <div class="card-content center-align" id="updatesContainer">
                        <div id="updateSpinner" class="hide">
                            <br/>
                            <div class="row">
                                <div class="col m6 offset-m3">
                                    <div class="progress">
                                        <div class="indeterminate"></div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <h4>Update in progress</h4><br/>
                            <p>We are downloading your updates. <br/>Please, don't close this window. <br/>We will do that after the updates. <br/><br/><i>-Thanks</i></p>
                        </div>
                        <div id="updateMessage"></div>
                        <div id="updateControls" class="hide">

                        </div>
                        <br/><br/>
                    </div>
                </div>
                <div class="center-align">
                    <br/>
                    <p class="grey-text text-darken-2" style="text-align: center;" id="versionHolder">PHPC v{{ settings(true)->version }}</p>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')

<script>
    let updateSpinner = $('#updateSpinner');
    let updateControls = $('#updateControls');
    let updateMessage = $('#updateMessage');
    let Card = $('.dymanic-card');
    let versionHolder = $('#versionHolder');

    updateControls.hide();
    updateSpinner.removeClass('hide');
    Card.addClass('card');

    axios.post('/do-updates')
        .then(response => {
            let result = response.data;
            let msg = '';

            if (result.error === null) {
                msg += `<span class="btn-floating z-depth-2 btn-large center-align green"><i class="material-icons">check</i></span><br/><br/>`;
                msg += `<h5><span>${result.message}</span></h5>`;
                msg += `This will close after 15seconds`;
                versionHolder.html(`Installed v${result.versions.available_version}`);
            } else {
                msg += `<span class="btn-floating z-depth-2 btn-large center-align red"><i class="material-icons">close</i></span><br/>`;
                msg += `<h5><span>${result.error}</span></h5>`;
                versionHolder.html(`PHPC v${result.versions.user_version}`);
                setTimeout(function () {
                    if (closeAndClearSession()) {
                        close();
                    }
                }, 3000);
            }

            msg += `<br/><br/><a href="" class="btn white darken-2 red-text closeUpdate" id="closeUpdate"><i
                    class="material-icons left">close</i> Close</a>`;

            updateSpinner.addClass('hide');
            updateMessage.html(msg);

            setTimeout(function () {
                if (closeAndClearSession()) {
                    close();
                }
            }, 15000);

            /**
             * Clear update session and close window
             */
            $('#closeUpdate').click((e) => {
                if (closeAndClearSession()) {
                    close();
                }
                e.preventDefault();
            });
            console.log(result);

        }).catch(error => console.log(error.response));

</script>