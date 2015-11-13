CheckUniqueName = {
    name : function() {
        return $('#shops_uniqueName').val();
    },
    sendName : function() {
        var check = $.post('/manager/createShop/uniqueName', { 'name' : this.name() });
        check.done(function(data) {
            var element = $('#checkName');
            if (data === '0') {
                element.text('+').show();
                element.addClass('plus');
                element.removeClass('minus');

                return true;
            } else {
                element.text('-').show();
                element.addClass('minus');
                element.removeClass('plus');

                return false;
            }
        });
    },
    checkName : function() {
        if (this.name().length > 4) {
            this.sendName();
            
            return true;
        } else {
            return false;
        }
    }
};
$(document).ready(function() {
    var element = $('#shops_uniqueName');
    element.focus(function(){
        CheckUniqueName.checkName();
    });
    element.keyup(function(){
        CheckUniqueName.checkName();
    });
    element.blur(function(){
        CheckUniqueName.checkName();
    });
});