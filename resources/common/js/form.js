$(document).ready(function(){
    const rules = $("#form-data").data("rules");
    const messages = $("#form-data").data("messages");

    $.validator.addMethod("checklower", function (value) {
        return value ? /[a-z]/.test(value) : true;
    });
    $.validator.addMethod("checkupper", function (value) {
        return value ? /[A-Z]/.test(value) : true;
    });
    $.validator.addMethod("checkdigit", function (value) {
        return value ? /[0-9]/.test(value) : true;
    });
    $.validator.addMethod("checkspecialcharacter", function (value) {
        return value ? /[%#@_\-]/.test(value) : true;
    });
    $.validator.addMethod("validPhone", function (value, element) {
        return this.optional(element) || /^(\+84|0)[3|5|7|8|9][0-9]{8}$/.test(value);
    }, "Số điện thoại không hợp lệ");

    $.validator.addMethod("greaterThanImportPrice", function(value, element) {
        var price_import = parseFloat($('#price_import').val());
        var price_sell = parseFloat(value);
        
        // Kiểm tra nếu giá bán lớn hơn giá nhập
        return this.optional(element) || price_sell > price_import;
    }, "Giá bán phải lớn hơn giá nhập.");

    $("#form__js").validate({
        rules: rules ?? "",
        messages: messages ?? "",
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-input-custom').append(error);
        },
        submitHandler: (form) => {
            form.submit();
            $('#loading__js').css('display', 'flex');
        },
    });

    // Add name file to input file
    // $(document).on('change', '.inputFile__js', function(){
    //     let nameFile = String($('.inputFile__js').val());
    //     if (nameFile == '' || nameFile == null) {
    //         $('.custom-file-label').text('Chọn hình ảnh');
    //     } else {
    //         $('.custom-file-label').text(nameFile.split('\\')[2]);
    //     }
    // });
});