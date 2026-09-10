$(document).ready(function(){
    let dateParam = getQueryParam('reservation')
    if (dateParam === null) {
        dateParam = getFirstAndLastDayOfCurrentMonth();
    }
    $('#reservation').daterangepicker(
        {
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Áp dụng',
                cancelLabel: 'Hủy',
                monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
            },
            startDate: dateParam.firstDay,
            endDate: dateParam.lastDay,
        }
    )
})
function getFirstAndLastDayOfCurrentMonth() {
    const now = new Date();

    // Lấy năm và tháng hiện tại
    const year = now.getFullYear();
    const month = now.getMonth();

    // Ngày đầu tiên của tháng hiện tại
    const firstDay = new Date(year, month, 1);

    // Ngày cuối cùng của tháng hiện tại
    const lastDay = new Date(year, month + 1, 0);

    return {
        firstDay: firstDay + '/' + (month + 1) + '/' + year,
        lastDay: lastDay + '/' + (month + 1) + '/' + year
    };
}
function getQueryParam(param) {
    const url = window.location.href;
    // Tạo một đối tượng URL từ chuỗi URL
    const urlObj = new URL(url);

    // Sử dụng phương thức searchParams để lấy giá trị của tham số
    const paramValue = urlObj.searchParams.get(param);

    if (paramValue === null) {
        return null;
    }

    return {
        firstDay: paramValue.split(' - ')[0],
        lastDay: paramValue.split(' - ')[1]
    };
}
