$(document).ready(function(){
    getAddress()
    $.validator.addMethod("validPhone", function (value, element) {
        return this.optional(element) || /^(\+84|0)[3|5|7|8|9][0-9]{8}$/.test(value);
    }, "Số điện thoại không hợp lệ");

    $("#form__js").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            name: {
                required: true,
                minlength: 1,
                maxlength: 30
            },
            apartment_number: {
                required: true
            },
            city: {
                required: true
            },
            district: {
                required: true
            },
            ward: {
                required: true
            },
            phone_number: {
                required: true,
                validPhone: true // Sẽ thêm phương thức kiểm tra số điện thoại hợp lệ
            }
        },
        messages: {
            name: {
                required: "Họ và tên là bắt buộc.",
                minlength: "Họ và tên phải có ít nhất 1 ký tự.",
                maxlength: "Họ và tên không được dài quá 30 ký tự."
            },
            email: {
                required: "Email là bắt buộc.",
                email: "Email không hợp lệ."
            },
            phone_number: {
                required: "Số điện thoại là bắt buộc.",
                validPhone: "Số điện thoại không hợp lệ."
            },
            city: {
                required: "Tỉnh, thành phố là bắt buộc."
            },
            district: {
                required: "Quận, huyện là bắt buộc."
            },
            ward: {
                required: "Phường, xã là bắt buộc."
            },
            apartment_number: {
                required: "Số nhà là bắt buộc."
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        submitHandler: (form) => {
            form.submit();
        },
    });

    $.ajaxSetup({
        headers: {
            token: "24d5b95c-7cde-11ed-be76-3233f989b8f3"
        },
    });
    getProvind();
    $(document).on('change', '#city', function(){
        $('#district').html("");
        $('#ward').html("");
        //get list province
        getProvind();
    });

    $(document).on('change', '#district', function(){
        $('#ward').html("");
        // get list ward
        getWard();
    });
});
// fucntion get district
function getProvind()
{
    let provinceId = $('#city').val();
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/district',
        data: {
            province_id: provinceId
        }
    }).done((respones) => {
        let option = '';
        //add data to district select
        respones.data.forEach(element => {
            option = `<option value="${element.DistrictID}">${element.DistrictName}</option>`
            $('#district').append(option);
        });
        getWard();
    });
}

//function get ward
function getWard()
{
    let district_id  = $('#district').val();
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/ward',
        data: {
            district_id : district_id 
        }
    }).done((respones) => {
        let option = '';
        //add data to ward select
        respones.data.forEach(element => {
            option = `<option value="${element.WardCode}">${element.NameExtension[0]}</option>`
            $('#ward').append(option);
        });
        getFee()
        getAddress()
    });
}

function getFee()
{
    let shop_id = "3577591";
    let from_district = "2027";
    let to_district = $('#district').val();
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services',
        data: {
            shop_id: shop_id,
            from_district: from_district,
            to_district: to_district
        }
    }).done((respones) => {
        let from_district = "2027";
        let service_type = respones.data[0].service_type_id;
        let to_district_id = $('#district').val();
        let to_ward_code = $('#ward').val();
        let data = {
            service_type_id: service_type,
            insurance_value: 500000,
            coupon: null,
            from_district_id: from_district,
            to_district_id: to_district_id,
            to_ward_code: to_ward_code,
            height:15,
            length:15,
            weight:1000,
            width:15
        }

        $.ajax({
            type: 'GET',
            url: 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee',
            data: data
        }).done((respones) => {
            let fee = parseInt(respones.data.total);
            let totalProduct = parseInt($('#total-order-const').val());
            $('#fee').text(new Intl.NumberFormat().format(fee));
            $('#total-order').text(new Intl.NumberFormat().format(fee + totalProduct));
            $('#total-order-input').val(fee + totalProduct)
        });
    });
}

function getAddress()
{
    let ward = $('#ward option:selected').text()
    let district = $('#district option:selected').text()
    let city = $('#city option:selected').text()
    let apartment_number = $('#apartment_number').val()
    $('#address').val(apartment_number + ', ' + ward + ', ' + district + ', ' + city)
}