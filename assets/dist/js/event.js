$(document).ready(function () {
    // 'index' page load more events
    $('#load_more_events').on('click', function() {
        $('.event-list-table').find('.hidden:gt(-4)').removeClass('hidden').addClass('new-item');

        if (!$('.event-list-table').find('.hidden:gt(-4)').length) {
            $('#load_more_events').addClass('hidden');
        }
    });

    if ($('.event-list-table').find('.hidden:gt(-4)').length) {
        $('#load_more_events').removeClass('hidden');
    }

    // 'view' oldalon a nap allito
    if ($('#participate_days').length) {
        $('#participate_days').multiselect({
            buttonWidth: '100%',
            numberDisplayed: 1,
            selectAllNumber: false,
            onChange: function (option, checked) {
                // legalabb 1-et ki kell valasztania
                var selectedOptions = $('#participate_days option:selected');
                if (selectedOptions.length < 1) {
                    $('#participate_days').multiselect('select', option.val());
                }
            },
            onDropdownHide: function (event) {
                $.post(setAttendOptionsUrl, {
                    days: $('#participate_days').val()
                }).fail(function () {
                    alert('Saving the days failed, please try again!');
                });
            }
        });
    }

    // 'update / create' oldalon a textarea
    $('#summernote').summernote({
        height: 400,
        minHeight: null,
        maxHeight: null,
        lang: 'hu-HU',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link'/* , 'picture', 'video' */]],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontSizes: ['10', '11', '12', '14', '16', '18', '24', '36']
    });
});

/**
 * 'view' oldalon a szerep allito
 * @param {String} role a szerep id-ja
 */
function setParticipateRole(role)
{
    $.post(setAttendOptionsUrl, {
        role: role
    }).fail(function () {
        alert('Saving the role failed, please try again!');
    }).done(function (resp) {
        if (resp.success == 1) {
            $('#role_title').html(resp.selectedRole);
        } else {
            alert('Saving the role failed, please try again!');
        }
    });
}