class UsersList {

    constructor(ajaxId, document) {
        this.ajaxId = ajaxId;
        this.componentBlock = $(document).find('.js-component[data-component-id=' + ajaxId + ']');
    }

    init() {
        var componentId = this.ajaxId;
        var selfComponentBlock = this.componentBlock;
        selfComponentBlock.on('click', '.page-link', function (e) {
            e.preventDefault();
            var action = $(this).data('action');
            var pageNum = $(this).data('cur-page');

            $.ajax({
                method: 'POST',
                dataType: 'html',
                data: {componentId: componentId, action: action, pageNum: pageNum},
                success: function (data) { // если запрос успешен вызываем функцию
                    if (data) {
                        selfComponentBlock.find('.js-body-container').html($(data).find('.js-body-container').html());

                    }

                }

            });
        });


        selfComponentBlock.on('click', '.js-import-document', function (e) {
            e.preventDefault();
            var documentType = $(this).data('type');
            var progressBarElement = $(this).find('.progress');
            var isAlreadyImport = $(this).attr('data-is-import');

            var selfBlock = $(this);
            if (isAlreadyImport === "0") {
                $(this).attr('data-is-import',1);
                $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        componentId: componentId,
                        documentType: documentType,
                        curStep: 0
                    },
                    success: function (data) { // если запрос успешен вызываем функцию
                        if (data['success']) {
                            progressBarElement.show();
                            importStep(progressBarElement, data['curStep'], data['maxStepCount'], documentType, componentId, data['documentName'],selfBlock,document, importStep)
                        }

                    }

                });

            }
        });

        function importStep(progressBarImport, curStepCount, maxStepCount, documentType, componentId, documentName,selfBlock,document, func) {
            if (curStepCount < maxStepCount) {

                $.ajax({
                    type: 'post',
                    data: {
                        componentId: componentId,
                        documentType: documentType,
                        curStep: curStepCount,
                        documentName: documentName
                    },
                    dataType: 'json',
                    success: function (data) { // если запрос успешен вызываем функцию

                        if (data['success']) {


                            progressBarImport.find('.progress-bar').width(parseInt(100 * (curStepCount / maxStepCount)) + '%');


                            func(progressBarImport, data['curStep'], maxStepCount, documentType, componentId, documentName,selfBlock, document,func);
                        }

                    },


                });


            } else {
                progressBarImport.find('.progress-bar').width('0%');
                progressBarImport.hide();
                selfBlock.attr('data-is-import',"0");
                var a = document.createElement("a");

                a.href = documentName;
                a.download = '';
                a.click();
            }

        }


    }


}

