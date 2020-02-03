$(document).ready(function() {
    // 'view' oldalon a nap allito
    if ($('#participate_days').length) {
        $('#participate_days').multiselect({
            buttonWidth: '100%',
            allSelectedText: 'Végig',
            numberDisplayed: 1,
            nSelectedText: 'nap',
            selectAllNumber: false,
            onChange: function(option, checked) {
                // legalabb 1-et ki kell valasztania
                var selectedOptions = $('#participate_days option:selected');
                if (selectedOptions.length < 1) {
                    $('#participate_days').multiselect('select', option.val());
                }
            },
            onDropdownHide: function(event) {
                $.post(setAttendOptionsUrl, {
                    days: $('#participate_days').val()
                }).fail(function() {
                    alert('Nem sikerült elmenti a napokat, próbáld újra!');
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
function setParticipateRole(role) {
    $.post(setAttendOptionsUrl, {
        role: role
    }).fail(function() {
        alert('Nem sikerült elmenti a szerepet, próbáld újra!');
    }).done(function(resp) {
        if (resp.success == 1) {
            $('#role_title').html(resp.selectedRole);
        } else {
            alert('Nem sikerült elmenti a szerepet, próbáld újra!');
        }
    });
}