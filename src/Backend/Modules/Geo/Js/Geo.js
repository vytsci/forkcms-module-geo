jsBackend.Geo = {
    init: function () {
        $('.jsGeoSelect').each(function () {
            jsBackend.Geo.parseCountries($(this));
        });
    },
    parseCountries: function ($target) {
        $target.prop('disabled', true);
        jsBackend.Geo.renderLoader($target);

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
                    .html(jsBackend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false)
                    .on('change', function(e) {
                        jsBackend.Geo.parseStates($selectStates, $target.val());
                    });

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsBackend.Geo.removeLoader($target);
            },
            error: function(jqXHR, textStatus){
                //location.reload();
            }
        });
    },
    parseStates: function ($target, id) {
        $target.prop('disabled', true);
        jsBackend.Geo.renderLoader($target);

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
                    .html(jsBackend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false)
                    .on('change', function(e) {
                        jsBackend.Geo.parseCities($selectCities, $target.val());
                    });

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsBackend.Geo.removeLoader($target);
            },
            error: function(jqXHR, textStatus){
                //location.reload();
            }
        });
    },
    parseCities: function ($target, id) {
        $target.prop('disabled', true);
        jsBackend.Geo.renderLoader($target);

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
                    .html(jsBackend.Geo.renderSelectOptions(data.data))
                    .prop('disabled', false);

                var selected = $target.data('selected');
                if (typeof selected != 'undefined') {
                    $target.val(selected);
                }

                if ($target.val() != '') {
                    $target.trigger('change');
                }

                jsBackend.Geo.removeLoader($target);
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

$(jsBackend.Geo.init);
