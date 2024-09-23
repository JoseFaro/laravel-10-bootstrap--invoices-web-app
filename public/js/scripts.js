$(function(){
    function initBillableCheckboxHandler() {
        var billableBox = $("#billableCheckboxHandler");
        var paidBox = $("#paidCheckboxHandler");
        var paymentDateBox = $("#paymentDateInputHandler");

        if (billableBox && paidBox) {
            billableBox.find('input').change(function() {
                var checkbox = $(this);
                var isChecked = checkbox.is(':checked');
                var checkedValue = isChecked ? 'disabled' : '';

                paidBox.find('input').prop('disabled', checkedValue);
                paidBox.find('input').prop('checked', '');

                if (isChecked) {
                    paymentDateBox.find('input').prop('disabled', 'disabled');
                    paymentDateBox.find('input').val('');
                } else {
                    paymentDateBox.find('input').prop('disabled', '');
                }
            })
        }
    }

    function initConfirmDeletionHandler() {
        $(".app-confirm-delete").click(function(e){
            e.preventDefault();

            var link = $(this);
            var form = link.closest('td').find('form');
            
            if (confirm('Eliminar?')) {
                form.submit();
            }
        });
    }

    function initCreateExpenseFromActivityHandler() {
        var expenseCheckboxTriggerId = "#createExpenseInput";
        var expenseContainerId = "#create-expense-container";

        $(expenseCheckboxTriggerId).change(function(){
            var checkbox = $(this);
            var isChecked = checkbox.is(':checked');

            if (isChecked) {
                $(expenseContainerId).css('display', 'block');
            } else {
                $(expenseContainerId).css('display', 'none');
            }
        });

        var checkedOnLoad = $(expenseCheckboxTriggerId).is(':checked');

        if (checkedOnLoad) {
            $(expenseContainerId).css('display', 'block');
        }
    }

    function initDatepickers() {
        $(".app-datepicker").each(function(){
            $(this).datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es',
            }); 
        });
    }

    function initDynamicSelects(requestOnStart) {
        $(".app-select-trigger").each(function(){
            var triggerSelect = $(this);
            var placeholderRoute = triggerSelect.data('route-placeholder');
            var targetSelectClass = triggerSelect.data('target-select');
            var formContainer = triggerSelect.closest('.app-form');

            var targetSelect = false;

            if (formContainer) {
                targetSelect = !!targetSelectClass ? formContainer.find(targetSelectClass + ':first') : false;
            }

            triggerSelect.change(function() {
                var select = $(this);
                var value = select.val();
                var route = `${placeholderRoute}/${value}`;
                
                $.get(route, function(data) {
                    if (targetSelect) {
                        targetSelect.html('');
                        
                        var defaultOption = $('<option value="">--</option>');
                        targetSelect.append(defaultOption);

                        if (data.length) {
                            for(var i = 0; i < data.length; i++) {
                                var item = data[i];
                                var option = $(`<option value="${ item.optionId }">${ item.optionName }</option>`);

                                targetSelect.append(option);
                            }
                        }

                        // updates related sibling target on clear
                        var updateRelatedTargetOnClear = !!targetSelect.data('update-related-target-on-clear');

                        if (updateRelatedTargetOnClear) {
                            var relatedTargetSelectClass = targetSelect.data('target-select');
                            var relatedTargetSelect = !!relatedTargetSelectClass ? formContainer.find(relatedTargetSelectClass + ':first') : false;

                            if (relatedTargetSelect) {
                                relatedTargetSelect.html('');

                                var defaultOption = $('<option value="">--</option>');
                                relatedTargetSelect.append(defaultOption);
                            }
                        }
                    }
                }, 'json');
            });
        });
    };

    function initFirstInputFocus() {
        var form = $(".app-form").first();
        var inputs = form.find('input,select');
                
        if (inputs) {
            var wasFound = false;

            inputs.each(function(){
                var input = $(this);
                var isFormControlInput = input.hasClass('form-control');
                var isFormSelectInput = input.hasClass('form-select');
                var isValidInput = isFormControlInput || isFormSelectInput;

                if (!wasFound && isValidInput) {
                    input.focus();
                    wasFound = true;
                }
            });
        }
    }

    function invoiceActivitiesHandler() {
        var select = $("#invoiceClientInput");
        var table = $('#invoice-activities-container .table').first();

        if (select && table) {
            var route = select.data('route');

            select.change(function() {
                var clientId = select.val();
                var requestUrl = `${route}/${clientId}`;

                $.get(requestUrl, function(data) {
                    var tbody = table.find('tbody');
                    tbody.html('');

                    if (data && data.activities && data.activities.length) {
                        for(var row of data.activities) {
                            tbody.append(
                                `
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="activities[]" value="${row.id}">
                                        </div>
                                    </td>
                                    <td>${row.id}</td>
                                    <td>${row.unit?.name}</td>
                                    <td>${row.site?.name}</td>
                                    <td>
                                        <span>${row.site_service?.service?.name}</span>
                                        <br>
                                        <div class="text-muted app-font-tiny">
                                            Precio sugerido ${row.site_service?.cost}
                                        </div>
                                    </td>
                                    <td>${row.cost}</td>
                                    <td>${row.date}</td>
                                    <td>${moment(row.created_at).format('YYYY-MM-DD HH:mm:ss')}</td>
                                    <td>${moment(row.updated_at).format('YYYY-MM-DD HH:mm:ss')}</td>
                                </tr>
                                `
                            );
                        }
                    }
                }, 'json');
            });
        }
    }

    initBillableCheckboxHandler();
    initConfirmDeletionHandler();
    initCreateExpenseFromActivityHandler();
    initDatepickers();
    initDynamicSelects();
    initFirstInputFocus();
    invoiceActivitiesHandler();
});