@include('inc.header')
<body class="{{ settings(true)->theme_type }} {{ settings(true)->theme }}">
<div id="app">
    {{--<div class="col m12" style="margin: 20px;">--}}
        {{--<a href="/">--}}
            {{--<span class="btn-floating purple"><i class="material-icons">arrow_back</i></span> Go back--}}
        {{--</a>--}}
    {{--</div>--}}
    <div class="container">
        <div class="row">

            <div class="col l6 offset-l3 m8 offset-m2" style="margin-top: 80px">
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
                            <h4>Please wait...</h4>
                            <p>We are checking for updates. <br/>This may take few minutes</p>
                            <br/><br/><a href="" class="btn purple closeUpdate" id="closeUpdate"><i class="material-icons left ">autorenew</i> Refresh</a>
                        </div>
                        <div id="updateMessage"></div>
                        <div id="updateControls" class="hide">
                            <h5>Check for updates</h5><br/><br/>
                            <a href="{{ url('updates') }}" id="checkUpdates" class="btn">Check for updates</a>
                        </div>
                        <br/><br/>
                    </div>
                </div>
                <div class="center-align">
                    <br/>
                    <p class="grey-text text-darken-2" style="text-align: center;">PHPC v{{ settings(true)->version }}</p>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')

<script>
    let baseUrl = '{{ url('/') }}';
    let updateSpinner = $('#updateSpinner');
    let updateControls = $('#updateControls');
    let updateMessage = $('#updateMessage');
    let Card = $('.dymanic-card');
    updateControls.hide();
    updateSpinner.removeClass('hide');
    Card.addClass('card');
    axios.post('/autocheck-updates')
    .then(response => {
        let result = response.data;
        let msg = '';
        if (result.error === null) {
            msg += `<span class="btn-floating btn-large z-depth-2 center-align green"><i class="material-icons">check</i></span><br/><br/>`;
            msg += `<h5><span>${result.message}</span></h5><br/>`;
            if (result.updated === false) {
                msg += `
                <br/><br/><a href="${baseUrl}/download-updates/" class='btn purple' style="margin-right: 10px;">Update</a>
                `;
            }
        } else {
            msg += `<span class="btn-floating z-depth-2 btn-large center-align red"><i class="material-icons">close</i></span><br/>`;
            msg += `<h5><span>${result.error}</span></h5><br/><br/>`;
        }

        msg += `<a href="" class="btn white darken-4 red-text closeUpdate" onclick="closeWindow();" id="closeUpdate"><i
                    class="material-icons left">close</i> Close</a>`;

        updateSpinner.addClass('hide');
        updateMessage.html(msg);
        console.log(result);

    }).catch(error => console.log(error.response));

</script>