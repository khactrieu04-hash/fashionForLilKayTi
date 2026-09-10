$(document).ready(function () {
    const infoCustomer = JSON.parse($("#data-order").attr("info-customer"));
    const products = JSON.parse($("#data-order").attr("products"));
    const today = new Date();
    const day = today.getDate();
    const month = today.getMonth() + 1; // Nhớ cộng thêm 1 vì tháng bắt đầu từ 0
    const year = today.getFullYear();
    let totalOrder = 0
    let orderDetailHtml = ''
    products.forEach(product => {
        orderDetailHtml += `
            <tr>
                <td>${product.id}</td>
                <td>${product.product_name}</td>
                <td>${product.color_name}</td>
                <td>${product.size_name}</td>
                <td>${product.quantity}</td>
                <td>${parseInt(product.unit_price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                <td>${(parseInt(product.quantity) * parseInt(product.unit_price)).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
            </tr>
        `;
        totalOrder += parseInt(product.quantity) * parseInt(product.unit_price)
    });
    const template = `
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>YeenOkShop</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        </head>
        <style>
            body {
                font-weight: 400;
                font-size: 12px;
            }

            .info {
                font-weight: 500;
                padding-right: 10px;
            }
            p {
                font-weight: 500;
            }
        </style>

        <body>
            <div class="mt-3 card">
                <h2 class="text-center mb-3 mt-3">HÓA ĐƠN BÁN HÀNG</h2>
                <p class="text-end" style="padding-right: 10px;">Ngày ${day} tháng ${month} năm ${year}</p>
                <div class="card">
                    <div class="card-body">
                        <p>Thông tin người bán</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Tên công ty</th>
                                    <th scope="col">Mã số thuế</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Địa chỉ</th>
                                    <th scope="col">Số điện thoại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>LANH XINH ĐẸP</td>
                                    <td>999999999</td>
                                    <td>lanhxinhdep@gmail.com</td>
                                    <td>HÀ NAM</td>
                                    <td>0545454444</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p>Thông tin người khách hàng</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Tên khách hàng</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Địa chỉ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${infoCustomer.name}</td>
                                    <td>${infoCustomer.phone_number}</td>
                                    <td>${infoCustomer.email}</td>
                                    <td>${infoCustomer.apartment_number + ', ' + infoCustomer.ward + ', ' + infoCustomer.district + ', ' + infoCustomer.city}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <p>Thông tin sản phẩm</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Mã SP</th>
                                    <th scope="col">Tên SP</th>
                                    <th scope="col">Màu</th>
                                    <th scope="col">Kích thước</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Đơn giá</th>
                                    <th scope="col">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                ` + orderDetailHtml +`
                                <tr>
                                    <td colspan="6" class="text-center">Tổng tiền sản phẩm</td>
                                    <td>` + totalOrder.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }) + `</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">Phí vận chuyển</td>
                                    <td>${infoCustomer.orders_transport_fee.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">Phương thức thanh toán</td>
                                    <td>${infoCustomer.payment_name}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">Tổng tiền đơn hàng</td>
                                    <td>${((parseInt(totalOrder) + parseInt(infoCustomer.orders_transport_fee))).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `;

    $(document).on('click', '#print-order', function(){
        const printWindow = window.open('', '_blank', `width=${screen.width},height=${screen.height},toolbar=no,scrollbars=no`);
        printWindow.document.write(template)
        // Đợi tài liệu tải hoàn toàn trước khi in
        printWindow.document.close(); // Đóng tài liệu để kết thúc quá trình ghi
        printWindow.focus(); // Chuyển tiêu điểm sang cửa sổ in

        // Gọi lệnh in và đóng cửa sổ khi in xong
        printWindow.print();
        printWindow.onafterprint = function() {
        printWindow.close();
    };
    })
    
});