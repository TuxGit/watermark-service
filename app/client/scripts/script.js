var common = {

    $inputFile: '.js-input-file',
    $inputTextFile: '.js-input-txt-file',
    $uploadBlock: '.js-upload-block',
    $uploadBtn: '.js-upload-btn',
    $switchBtn: '.js-switch-btn',

    initEvents: function() {
        var self = this;
        $(window).on({
            load: function() {
                // self.initRangeSlider();

                $(self.$uploadBtn).on('click', $.proxy(self.opnUploadWindow, self));
                // $(self.$inputFile).on('change', $.proxy(self.setFileName, self));
                $(self.$switchBtn).on('click', $.proxy(self.showCurrentBox, self));
                $('.social__btn--like').on('click', $.proxy(self.showSocialBtns, self));
            }
        });
    },

    opnUploadWindow: function(e) {
        var $this = $(e.currentTarget);
        $this.closest(this.$uploadBlock).find(this.$inputFile).click();
    },

    setFileName: function(e) {
        var $this = $(e.currentTarget);
        $this.closest(this.$uploadBlock).find(this.$inputTextFile).val($this.val().split('\\').pop());
    },

    showCurrentBox: function(e) {
        var $this = $(e.currentTarget);

        $this.addClass('btn--active')
            .siblings().removeClass('btn--active')
            .closest('.setting__position').find('.block--' + $this.data('div')).addClass('active')
            .siblings().removeClass('active');

        e.preventDefault();
    },

    initRangeSlider: function() {
        // $(".range-slider").slider({
        //     range: "min",
        //     value: 100,
        //     min: 1,
        //     max: 100
        // });
    },

    showSocialBtns: function(e) {
        e.preventDefault();
        $(e.currentTarget).closest('.socials').toggleClass('social--open');
    }
};

///////////////////////////////////////

var App = {
    // image: null,
    // watermark: null,
    settings: {
        max_w: 650,
        max_h: 535
    },
    image: {
        width: 650,
        height: 535,
        el: $('.image')
    },
    watermark: {
        width: 200,
        height: 200,
        el: $('.watermark')
    },
    position: {
        top: 0,
        left: 0
    },
    opacity: 100,
    type: "one",

    setImg: function (type, data)
    {
        if (type == 'image') {
            this.image = data;
            this.image.el = $('.image');
            this.image.el.css('background-image', data.url);
            // this.image.el.css('opacity', 1);

            var width = data.width, 
                height = data.height;
            if ( (data.width > this.settings.max_w) || (data.width > this.settings.max_w) ) {
                console.log('смаштабируй!');
            }
            this.image.el.css('width', width);
            this.image.el.css('height', height);
        } else if (type == 'watermark') {
            this.watermark = data;
            this.watermark.el = $('.watermark');
            this.watermark.el.css('background-image', data.url);
            this.watermark.el.css('width', data.width);
            this.watermark.el.css('height', data.height);
        }
    },

    setPos: function (obj)
    {
        if (obj.x || (obj.x === 0) ) {
            if (typeof(obj.x) == 'number') //string
                this.position.left = obj.x;
            else
                this.position.left = this.getCoords('x', obj.x);
        } 
        if (obj.y || (obj.y === 0) ) {
            if (typeof(obj.y) == 'number')
                this.position.top = obj.y;
            else
                this.position.top = this.getCoords('y', obj.y);
        }
        this.watermark.el.css('left',  this.position.left);
        this.watermark.el.css('top',  this.position.top);
        $('#x').spinner( "value", App.position.left );
        $('#y').spinner( "value", App.position.top );
    },

    getCoords: function (dir, str)
    {
        var out = 0;
        if (dir == 'x') {
            switch(str) {
                case 'left':
                    out = 0;
                    break;
                case 'center':
                    out = this.image.el.width()/2 - this.watermark.el.width()/2;
                    break;
                case 'right':
                    out = this.image.el.width() - this.watermark.el.width();
                    break;
            }
        } else if (dir == 'y') {
            switch(str) {
                case 'top':
                    out = 0;
                    break;
                case 'middle':
                    out = this.image.el.height()/2 - this.watermark.el.height()/2;
                    break;
                case 'bottom':
                    out = this.image.el.height() - this.watermark.el.height();
                    break;
            }
        }
        return out;
    },

    clearForm: function ()
    {
        $(".range-slider").slider('value', 100);

        // data-x="left" data-y="top" 
        // this.setPos({x: 0, y: 0});
        $('td').first().click();
    },

    submitForm: function ()
    {
        var data = {
            x: this.position.left,
            y: this.position.top,
            opacity: this.opacity
        };

        $.ajax({
            url: '/api/download',
            type: 'GET',
            dataType: 'json',
            data: data,
        })
        .done(function() {
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        
    }
}
window.App = App;


// http://learn.jquery.com/jquery-ui/widget-factory/extending-widgets/
$.widget( "ui.spinner", $.ui.spinner, {
    // open: function() {
    //     console.log( "open" );
    //     return this._super();
    // }
    _buttonHtml: function() {
        var html = 
            '<div class="regulate__trigger trigger--arrow">' +
                '<button data-step="x-up" type="button" class="btn--up ui-spinner-button ui-spinner-up">Больше</button>' +
                '<button data-step="x-down" type="button" class="btn--down ui-spinner-button ui-spinner-down">Меньше</button>' +
            '</div>';
        return html;
        // return "" +
        // "<button class='ui-spinner-button ui-spinner-up'>" +
        //   "<span class='ui-icon " + this.options.icons.up + "'>&#9650;</span>" +
        // "</button>" +
        // "<button class='ui-spinner-button ui-spinner-down'>" +
        //   "<span class='ui-icon " + this.options.icons.down + "'>&#9660;</span>" +
        // "</button>";
    },
    _uiSpinnerHtml: function() {
        return "<div class='my-spinner ui-spinner ui-widget ui-widget-content'></div>";
}
});


$(document).ready(function() {
    common.initEvents();

    // $('.ui-spinner-button').on('click', function(e) {
    //     e.preventDefault();
    //     // return this;
    // });

    // $(window).on('keyup', '#x', function(e) {
    //     e.preventDefault();
    //     var code = e.keyCode || e.which;
    //     console.log(e, code);
    //     if(code == 13) { //Enter keycode
    //         //Do something
    //         return false;
    //     }
    // });
    $(document).on('keyup', '#x', function (e) {
        // console.log(e);
        if (e.keyCode == 37 || e.keyCode == 39)
            return;
        var $el = $(e.target),
            value = $el.spinner('value');
        if ( (value <= $el.spinner('option', 'max')) && (value >= $el.spinner('option', 'min')) )
            App.setPos({x: value});
    });

    $(document).on('keyup', '#y', function (e) {
        if (e.keyCode == 37 || e.keyCode == 39)
            return;
        var $el = $(e.target),
            value = $el.spinner('value');
        if ( (value <= $el.spinner('option', 'max')) && (value >= $el.spinner('option', 'min')) )
            App.setPos({y: value});
    });

    $('.setting__buttons-reset').on('click', function () {
        App.clearForm();
    });

    $('.setting__buttons-submit').on('click', function (e) {
        e.preventDefault();
        App.submitForm();
    });

    $('.table__cell').on('click', function (e) {
        // console.log(e.target);
        $el = $(e.target);
        $('.table__cell').removeClass('cell--active');
        $el.addClass('cell--active');
        App.setPos({
            x: $el.data('x'),
            y: $el.data('y')
        });
    });

    // https://github.com/blueimp/jQuery-File-Upload/wiki/Basic-plugin
    $('#original-image').fileupload({
        dataType: 'json',
        url: '/_loftschool/dz-2.3/api-upload.json',
        done: function (e, data) {
            // console.log(e, data);
            // $.each(data.result.files, function (index, file) {
            //     $('<p/>').text(file.name).appendTo(document.body);
            // });

            status = data.jqXHR.status;
            if (status == 200) {
                resp = data.result; // data.jqXHR.responseJSON;
                App.setImg("image", resp);
            }
        },
        add: function (e, data) {
            // console.log(data);
            // data.context = $('<p/>').text('Uploading...').appendTo(document.body);
            // data.files[0].name
            $('#original-image').siblings('input').val(data.files[0].name);
            data.submit();
        }
    });
    $('#watermark').fileupload({
        dataType: 'json',
        url: '/_loftschool/dz-2.3/api-upload.json',
        done: function (e, data) {
            // console.log(e, data);

            status = data.jqXHR.status;
            if (status == 200) {
                resp = data.result; // data.jqXHR.responseJSON;
                App.setImg("watermark", resp);
            }
        },
        add: function (e, data) {
            $('#watermark').siblings('input').val(data.files[0].name);
            data.submit();
        }
    });

    // http://api.jqueryui.com/draggable/
    $( ".watermark" ).draggable({
        containment: "parent",
        stop: function( event, ui ) 
        {
            // console.log(ui.position);
            // App.position = ui.position;
            App.setPos({
                x: ui.position.left,
                y: ui.position.top
            });
        }
    });

    $(".range-slider").slider({
        range: "min",
        value: 100,
        min: 1,
        max: 100,
        slide: function( event, ui ) 
        {
            App.opacity = ui.value/100;
            $('.watermark').css('opacity', App.opacity);
        },
        change: function( event, ui ) 
        {
            App.opacity = ui.value/100;
            $('.watermark').css('opacity', App.opacity);
        }
    });

    $( "#x" ).spinner({
        min: 0,
        max: 650-200, // TODO
        step: 1,
        spin: function( event, ui ) 
        {
            App.setPos({'x': ui.value});
        },
        change: function( event, ui ) 
        {
            // ui == {}
            var $el = $(event.target);
            App.setPos({'x': $el.spinner('value')});
        }
    });

    $( "#y" ).spinner({
        min: 0,
        max: 535-200, // TODO
        step: 1,
        spin: function( event, ui ) 
        {
            App.setPos({'y': ui.value});
        },
        change: function( event, ui ) 
        {
            // ui == {}
            var $el = $(event.target);
            App.setPos({'y': $el.spinner('value')});
        }
    });

    // $('.ui-spinner a.ui-spinner-button').css('display','none');

});