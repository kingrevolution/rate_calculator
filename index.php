<!DOCTYPE html>
<html>

<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap-select.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap-select.css">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <title>Rate calculator/General rate info</title>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });
        });
        window.onunload = () => {
            // Clear the local storage on the tab/window close
            localStorage.clear();
        }
        // Navigation Timing API - clear local storage on reload
        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            localStorage.clear();
        }
    </script>
    <script>
        function select_limit() {
            var limit_value = $("#limit").val();
            var mode_value = $("#mode").val();;
            var prefix_value = $("#prefix").val();
            var country_value = $("#FormCountries").val();
            var current_page = 1;
            Table_load(prefix_value, country_value, mode_value, limit_value, current_page);
        };

        function select_page(current_page) {
            var limit_value = $("#limit").val();
            var mode_value = $("#mode").val();;
            var prefix_value = $("#prefix").val();
            var country_value = $("#FormCountries").val();
            Table_load(prefix_value, country_value, mode_value, limit_value, current_page);
        };

        function prefix_search() {
            var prefix_value = $("#prefix").val();
            var mode_value = $("#mode").val();
            var country_value = $("#FormCountries").val() ? $("#FormCountries").val() : 'empty';
            let stored_country_value = localStorage.getItem('country_value');
            let stored_prefix_value = localStorage.getItem('prefix_value');
            if (stored_country_value && stored_prefix_value) {
                if (stored_country_value == country_value) {
                    if (stored_prefix_value == prefix_value) {
                        return;
                    } else {
                        localStorage.setItem('prefix_value', prefix_value);
                    }
                } else {
                    localStorage.setItem('country_value', country_value);
                    localStorage.setItem('prefix_value', prefix_value);
                }
            } else if (stored_country_value && !prefix_value) {
                if (stored_country_value == country_value) {
                    return;
                } else {
                    localStorage.setItem('country_value', country_value);
                }
            } else {
                localStorage.setItem('prefix_value', prefix_value);
                country_value ? localStorage.setItem('country_value', country_value) : localStorage.setItem(
                    'country_value', 'empty');
            }
            Table_load(prefix_value, country_value, mode_value);
        };

        $(function initial() {
            $("#FormCountries").change(function () {
                var mode_value = $("#mode").val();
                $("#prefix").val('');
                var country_value = $(this).val();
                var current_page = 1;
            });

            $("#mode").change(function () {
                var mode_value = $(this).val();
                var prefix_value = $("#prefix").val();
                var country_value = $("#FormCountries").val();
                var limit_value = $("#limit").val();
                var current_page = $("#current_page").val();
                Table_load(prefix_value, country_value, mode_value, limit_value, current_page);
            });
        }).change();

        function Table_load(prefix, country, mode, limit, current_page) {
            country = country == 'empty' ? '' : country;
            var loading = $("#Loading");
            var table = $("#TableDisplay");
            if ($('#mode option').length > 1) $("#mode").show();
            table.hide();
            loading.show();
            $("#mode").prop('disabled', true);
            table.load(
                'table.php', {
                    prefix: prefix,
                    country: country,
                    mode: mode,
                    limit: limit,
                    current_page: current_page
                },
                function () {
                    loading.hide();
                    $("#mode").prop('disabled', false);
                    table.show();
                }
            );
        }
    </script>
</head>

<body>
    <div class="col-sm-5">
        <select id="FormCountries" class="selectpicker form-control" data-live-search="true">
            <option first value="">Please choose a country</option>
            <?php
                require_once 'country_list.php';
                foreach ($country_list as $key => $temp) {
                    echo '<option data-content="<img src=\'flags/' . $key . '.png\'> ' . $temp . '" value="' . $temp . '"></option>';
                }
            ?>
        </select>
        <input type="text" class="form-control" placeholder="Prefix" id="prefix">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="prefix_search()">Search</button>
        </span>
        <select id="mode" style=" display:none; " class="form-control">
            <?php
                require_once 'config.php';
                foreach ($report_modes as $index => $temp) {
                    echo '<option value="' . $index . '">' . $temp . '</option>';
                }
            ?>
        </select>
        <p><img id="Loading" style=" display:none; " src="ajax_preloader.gif"></p>
        <div id="TableDisplay"></div>
    </div>
</body>

</html>