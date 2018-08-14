@include('inc.header')
<body class="{{ settings(true)->theme_type }} {{ settings(true)->theme }}">
    <div class="container">
        <div id="app">
            <div class="row">
                <div class="col m6 offset-m3">
                    <div class="card dynamic-card black-text center-align">
                        <div class="card-content">
                            <h5>PHPC Terminal</h5>
                            <br/>
                            <p class="center-align">
                                Run any PHP and Laravel Artisan commands. Click the button below to continue.
                            </p><br/><br/>
                        </div>
                        <div class="card-action">
                            <center>
                                <a href="{{ url('/console/open') }}" id="openTerminal" class="btn center-align btn-rounded purple darken-3">Open Terminal <i class="material-icons right">navigate_next</i></a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('inc.footer')
    <script>
        $('#openTerminal').click(function (e) {
            window.close();
            window.location.href = "{{ url('console/open') }}";
        });
    </script>
</body>
