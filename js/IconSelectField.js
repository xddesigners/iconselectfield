
(function ($) {
    $.entwine('ss', function ($) {

        $('details.icon-select-group').entwine({
            onmatch() {
                // auto-open if a radio is already checked
                if (this.find('input[type="radio"]:checked').length) {
                    this.attr('open', 'open');
                }
            },
        });

    });
}(jQuery));
