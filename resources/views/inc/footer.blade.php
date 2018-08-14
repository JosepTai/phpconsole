<script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendors/blockui.js') }}"></script>
<script src="{{ asset('vendors/select2/dist/js/select2.min.js') }}"></script>
{{--<script src="{{ asset('vendors/jquery.mCustomScrollbar/jquery.mCustomScrollbar.min.js') }}"></script>--}}
<script src="{{ asset('vendors/keyboardJS/keyboard.min.js') }}"></script>
<script>
    const AppUrl = '{{ url('/') }}';
    const AppSettings = $.parseJSON({!! settings() !!});
</script>

<script src="{{ asset('vendors/ace/emmet-compiled.js') }}"></script>
<script src="{{ asset('vendors/ace/src/ace.js') }}"></script>
<script src="{{ asset('vendors/ace/src/ext-emmet.js') }}"></script>
<script src="{{ asset('vendors/ace/src/ext-language_tools.js') }}"></script>
<!-- load ace static_highlight extension -->
<script src="{{ asset('vendors/ace/src/ext-static_highlight.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>


</body>
</html>