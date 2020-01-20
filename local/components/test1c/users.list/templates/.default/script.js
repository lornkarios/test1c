
class UsersList {

    constructor(ajaxId,document) {
        this.ajaxId = ajaxId;
        this.componentBlock = $(document).find('.js-component[data-component-id='+ajaxId+']');
    }

    init(){
        var componentId = this.ajaxId;
        var selfComponentBlock = this.componentBlock;
        selfComponentBlock.on('click','.page-link',function (e) {
            e.preventDefault();
            var action = $(this).data('action');
            var pageNum = $(this).data('cur-page');

            $.ajax({
                method: 'POST',
                dataType: 'html',
                data: {componentId:componentId, action:action,pageNum:pageNum},
                success: function (data) { // если запрос успешен вызываем функцию
                    if(data) {
                         selfComponentBlock.html($(data).html());

                    }

                }

            });
        });
    }


}

