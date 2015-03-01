
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
                self.initRangeSlider();

                $(self.$uploadBtn).on('click', $.proxy(self.opnUploadWindow, self));
                $(self.$inputFile).on('change', $.proxy(self.setFileName, self));
                $(self.$switchBtn).on('click', $.proxy(self.showCurrentBox, self));
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
        $( ".range-slider" ).slider({
            range: "min",
            value: 100,
            min: 1,
            max: 100
        });
    }
};

$(document).ready(function() {
    common.initEvents();
});