
var common = {

    $inputFile: '.js-input-file',
    $inputTextFile: '.js-input-txt-file',
    $uploadBlock: '.js-upload-block',
    $uploadBtn: '.js-upload-btn',

    initEvents: function() {
        var self = this;
        $(window).on({
            load: function() {
                $(self.$uploadBtn).on('click', $.proxy(self.opnUploadWindow, self));
                $(self.$inputFile).on('change', $.proxy(self.setFileName, self));
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
    }
};

$(document).ready(function() {
    common.initEvents();
});