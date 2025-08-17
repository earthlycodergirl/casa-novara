(function($) {

    'use strict';

	/**
	 *
	 * @param {Object} options
	 * @param {string} options.counterMode - Values: time, numeric. Default numeric
	 * @param {Number} options.steps - The steps to increment the input value. If counter mode is time, the steps will increment as minutes. Default 30
	 * @param {Boolean} options.readonly
	 * @param {Number} options.speedOnMouseDown - Speed counter on mousedown. Set value in milliseconds
	 * @returns {*}
	 */
    $.fn.incremental = function(options) {

        var settings = $.extend({
            counterMode: 'time',     // Values: time, numeric. Default numeric
            steps: 30,                // The steps to increment the input value. If counter mode is time, the steps will increment as minutes. Default 30
            readonly: false,
            speedOnMouseDown: 10      // Speed counter on mousedown. Set value in milliseconds
        }, options);

        var btn_reduce = '<span class="input-group-btn"><button type="button" class="btn btn-white" data-operator="-">-</button></span>';

        var btn_increment  = '<span class="input-group-btn"><button type="button" class="btn btn-white" data-operator="+">+</button></span>';

        var operators = {
            '+' : function(a, b) { return a + b; },
            '-' : function(a, b) { return a - b; }
        };

        /**
         * Render the incremental input component
         *
         * @param element
         */
        function renderInput(element) {
            var input_html = btn_reduce;
            input_html += element.innerHTML.trim();
            input_html += btn_increment;

            element.innerHTML = input_html;

            $(element).addClass('input-group');
            $(element).find('input').prop({
                readonly: settings.readonly
            });
        }

        /**
         * Return a valid value. When counterMode is numeric, the value would be
         * returned as an integer and not as string. When nothing given, the default
         * value would be 0
         *
         * @param $input
         * @returns {number}
         */
        function getValue($input) {
            var input_value = 0;
            if ($input.val().length > 0) {
                input_value = settings.counterMode == 'numeric' ? parseInt($input.val()) : $input.val();
            }
            return input_value;
        }

        /**
         *
         * @param count
         * @param op
         * @returns {*}
         */
        function calculateCount(count, op) {
            switch (settings.counterMode) {
                case 'time':
					var maxCount = 24 * 60; // max count 24 hours
                    var minutes = operators[op]($.fn.incremental.hoursToMinutes(count), settings.steps);
                    if ((minutes < 0) || (minutes >= maxCount)) {
                        return count;
                    }
                    count = $.fn.incremental.minutesToHours(minutes);
                    break;

                case 'numeric':
                default:
                    var new_count = operators[op](count, settings.steps);
                    if (new_count < 0) {
                        return count;
                    }
                    count = new_count;
                    break;
            }
            return count;
        }

        /**
         * Initialize incremental input and attach click event to
         * the buttons and blur event to the input field
         *
         */
        return this.each(function(index, element) {
            renderInput(element);

            var $input = $(element).find('input'), count, counterInterval;

            // Calculate the count on click
            $(element).find('button').on('click', function() {
                var op = this.getAttribute('data-operator');
                count = calculateCount(getValue($input), op);
                $input.val(count);
                if($('body').hasClass('no-mobile')){
                   $('#loading_modal').modal('show');
                   $("#search_sidebar").submit().delay(800);
                }
            });

            //
            $input.on('blur', function() {
                count = (settings.counterMode == 'numeric') ? parseInt(this.value) : this.value.trim();
            });

            $(element).find('button').on('mousedown', function(event) {
                var op = this.getAttribute('data-operator');
                counterInterval = setInterval(function() {
                    var inputValue = getValue($input);
                    count = calculateCount(inputValue, op);
                    $input.val(count);
                }, settings.speedOnMouseDown);

            });

            $(document).on('mouseup', function(event) {
                clearInterval(counterInterval);
            });

            // Calculate the value when the mousewheel is used
            $input.on('wheel', function(e) {
                var event = e.originalEvent;
                var operator = event.wheelDelta > 0 ? '+' : '-';
                var value = getValue($input);
                count = calculateCount(value, operator);
                $input.val(count);
            });

            // #todo: Create regex to reject alpha characters
            //
            // Skip non-numeric characters in the field
            // $input.on('keydown keypress', function(event) {
            //     // if ((event.which >= 48 && event.which <= 57) || event.which == 12 || event.which == 8) {
            //     // 	return true;
            //     // }
            //     //
            //     // return false;
            //
            //     var regex = /([a-zA-Z]|[^:0-9])/g;
            //     var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            //     if (regex.test(key)) {
            //         $input.val(count);
            //         return false;
            //     }
            // });
        });
    };

    /**
     * Convert minutes to hours. Returns at formatted time string
     * Example: 150 minutes to 2:30
     *
     * @param {number} value
     * @returns {string}
     */
    $.fn.incremental.minutesToHours = function(value) {
        var hours = Math.floor(value / 60);
        var minutes = value % 60;
        if (minutes < 10) {
            minutes = '0' + minutes
        }
        return hours + ':' + minutes;
    };

    /**
     * Convert hours to minute. Example: 2:00 to 60
     *
     * @param {string} value
     * @returns {number}
     */
    $.fn.incremental.hoursToMinutes = function (value) {
        var time = value.split(':').map(Number);
        var minutes = 0;

        if (time.length == 2) {
            minutes = time[0] * 60 + time[1];
        }

        if (time.length == 1) {
            minutes = time[0] * 60;
        }

        return minutes;
    };

})(jQuery);
