define(['jquery'],
    function($) {
        function Timer()
        {
            "use strict";
            if (Timer._instance) {
                //this allows the constructor to be called multiple times
                //and refer to the same instance. Another option is to
                //throw an error.
                return Timer._instance;
            }
            Timer._instance = this;

            this.container = new Array(0);
            this.state = '';
            this.task = null;
            this.timeStamp = 0;

            this._loop = function()
            {
                if (this.state == 'stopped') {
                    return;
                }

                var miliseconds = 0;
                var seconds     = 0;
                var minutes     = 0;
                var hours       = 0;

                var currentTime = new Date();
                currentTime = currentTime.getTime();

                var timeDiff = currentTime - this.timeStamp;

                // calculate milliseconds
                miliseconds = timeDiff % 1000;
                if (miliseconds < 1) {
                    miliseconds = 0;
                } else {
                    // calculate seconds
                    seconds = (timeDiff - miliseconds) / 1000;
                    if (seconds < 1) {
                        seconds = 0;
                    } else {
                        // calculate minutes
                        var minutes = (seconds - (seconds % 60)) / 60;
                        if (minutes < 1) {
                            minutes = 0;
                        } else {
                            // calculate hours
                            var hours = (minutes - (minutes % 60))/60;
                            if (hours < 1) {
                                hours = 0;
                            }
                        }
                    }
                }

                // substract elapsed minutes & hours
                seconds  = seconds - (minutes * 60);
                minutes  = minutes - (hours * 60);
                var html = this.format(hours) + ":" + this.format(minutes) + ":" + this.format(seconds);
                // update display
                this.container.forEach(function(container) {
                    $('#' + container).html(html);
                });
                $('#' + this.container).html(html);
                setTimeout(this._loop.bind(this), 1000);
            };
        }

        Timer.getInstance = function ()
        {
            "use strict";
            return Timer._instance || new Timer();
        };

        Timer.prototype.getRecord = function (callback, getRecordUrl)
        {
            $.ajax({
                url: getRecordUrl,
                dataType: 'json',
                success: function(data) {
                    callback(data);
                }
            });
        };

        // Start timer
        Timer.prototype.start = function (startRecordUrl)
        {
            if (this.state == 'running') {
                return;
            }
            var myClass = this;
            $.ajax({
                url: startRecordUrl,
                dataType: 'json',
                async: false,
                success: function(data) {
                    myClass.processResponse(data);
                }
            });
        };

        // Initializes timer
        Timer.prototype.init = function (getRecordUrl)
        {
            if (this.state == 'running') {
                return;
            }
            var myClass = this;
            $.ajax({
                url: getRecordUrl,
                dataType: 'json',
                async: false,
                success: function(data) {
                    myClass.processResponse(data);
                }
            });
        };

        // Handle response data
        Timer.prototype.processResponse = function (data)
        {
            var currentTime = new Date();
            var totalSec = data['time'];
            this.timeStamp = currentTime.getTime() - totalSec * 1000;

            this.task = data;
            this.state = 'running';

            return this._loop();
        };

        // Stops timer and create worklog from tracker record,
        // after this deletes tracker
        Timer.prototype.stop = function (stopRecordUrl)
        {
            if (this.state !== 'running') {
                return;
            }

            var myClass = this;
            $.ajax({
                url: stopRecordUrl,
                type: 'POST',
                success: function() {
                    myClass.handleStop();
                }
            });
        };

        // Handle timer after it's stop
        Timer.prototype.handleStop = function ()
        {
            this.state = 'stopped';
            // update display
            var html =  "00:00:00";
            this.container.forEach(function(container) {
                $('#' + container).html(html);
            });
            this.container = new Array(0);
            return this._loop();
        };

        // Add time to dom element
        Timer.prototype.addContainer = function (container)
        {
            if (this.container.indexOf(container) == -1) {
                this.container.push(container);
            }
        };

        // Formats time units
        Timer.prototype.format = function (a)
        {
            if (a < 10) {
                a = '0' + a;
            }
            return a;
        };

        Timer.prototype.getState = function ()
        {
            return this.state;
        };

        Timer.prototype.getTask = function ()
        {
            return this.task;
        };

        return Timer;
    }
);
