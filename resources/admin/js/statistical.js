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
    const firstDayOfThreeMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 3, 1);
    const dayOf3MonthAgo = firstDayOfThreeMonthsAgo.getDate();
    const monthAgo = firstDayOfThreeMonthsAgo.getMonth() + 1;
    const yearOf3MonthAgo = firstDayOfThreeMonthsAgo.getFullYear();

    // Lấy năm và tháng hiện tại
    const year = now.getFullYear();
    const month = now.getMonth();

    // Ngày cuối cùng của tháng hiện tại
    const lastDay = new Date(year, month + 1, 0);
    return {
        firstDay: dayOf3MonthAgo + '/' + monthAgo + '/' + yearOf3MonthAgo,
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
