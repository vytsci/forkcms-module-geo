jsFrontend.Geo = {
    init: function () {
        $('.jsGeoSelect').each(function () {
            jsFrontend.Geo.parseCountries($(this));
        });
    },
    parseCountries: function ($target) {
        $target.prop('disabled', true);
        jsFrontend.Geo.renderLoader($target);

        var $selectStates = $($target.data('select-states'));
        $selectStates.prop('disabled', true);

        $.ajax({
            timeout: 60000,
            data:
            {
                fork: {
                    module: 'Geo', action: 'GetData'
                },
                section: 'countries'
            },
            success: function(data, textStatus)
            {
                $target
                    .html(jsFrontend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false)
                    .on('change', function(e) {
                        jsFrontend.Geo.parseStates($selectStates, $target.val());
                    });

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsFrontend.Geo.removeLoader($target);
            },
            error: function(jqXHR, textStatus){
                //location.reload();
            }
        });
    },
    parseStates: function ($target, id) {
        $target.prop('disabled', true);
        jsFrontend.Geo.renderLoader($target);

        var $selectCities = $($target.data('select-cities'));
        $selectCities.prop('disabled', true);

        $.ajax({
            timeout: 60000,
            data:
            {
                fork: {
                    module: 'Geo', action: 'GetData'
                },
                section: 'states',
                id: id
            },
            success: function(data, textStatus)
            {
                $target
                    .html(jsFrontend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false)
                    .on('change', function(e) {
                        jsFrontend.Geo.parseCities($selectCities, $target.val());
                    });

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsFrontend.Geo.removeLoader($target);
            },
            error: function(jqXHR, textStatus){
                //location.reload();
            }
        });
    },
    parseCities: function ($target, id) {
        $target.prop('disabled', true);
        jsFrontend.Geo.renderLoader($target);

        $.ajax({
            timeout: 60000,
            data:
            {
                fork: {
                    module: 'Geo', action: 'GetData'
                },
                section: 'cities',
                id: id
            },
            success: function(data, textStatus)
            {
                $target
                    .html(jsFrontend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false);

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsFrontend.Geo.removeLoader($target);
            },
            error: function(jqXHR, textStatus){
                //location.reload();
            }
        });
    },
    renderSelectOptions: function (data) {
        var result = '';

        result += '<option value="">-</option>';

        $.each(data, function (key, record) {
            result += '<option value="' + record.id + '">' + record.locale.name + '</option>';
        });

        return result;
    },
    renderLoader: function ($target) {
        $('<div class="loader">&nbsp;</div>').insertBefore($target);
    },
    removeLoader: function ($target) {
        $target.prev('.loader').remove();
    }
};

$(jsFrontend.Geo.init);
