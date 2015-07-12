
//ISC Functions
$(function(){
    $.nette.init();
});

$.nette.ext('onAjax', {
    before: function () {
        alarm.cancel();
    },
    start: function () {
        $("#snippet--repositories").addClass("searching");
    },
    success: function () {
        $("#snippet--repositories").removeClass("searching");
    }
});

var alarm = {
    remind: function(form) {
        $(form).trigger( "submit" );
        delete this.timeoutID;
    },

    setup: function(input) {
        this.cancel();
        var self = this;
        this.timeoutID = window.setTimeout(function(parameter) {self.remind(parameter);}, 400, input.parentNode);
    },

    cancel: function() {
        if(typeof this.timeoutID == "number") {
            window.clearTimeout(this.timeoutID);
            delete this.timeoutID;
        }
    }
};


