import Swal from 'sweetalert2';

$(document).ready(function(){
  var table;
  var columnsExport = [];
  var coloumnAction;
  // Count columns export
  Array.from($('#table-crud tr th')).forEach((child, index) => {
    columnsExport.push(index)
  });
  // Remove colomn action
  coloumnAction = columnsExport.pop();

  toast = toast();
  table = new DataTable('#table-crud', {
    layout: {
      topStart: 'buttons'
    },
    buttons: [
      { 
        extend: 'excel', 
        text: 'Xuất Excel', 
        exportOptions: {
            columns: columnsExport
        }
      }, 
      { 
        extend: 'pdf', 
        text: 'Xuất PDF', 
        exportOptions: {
            columns: columnsExport
        }
      }, 
    ],
    paging: true,
    lengthChange: false,
    searching: true,
    ordering: true,
    columnDefs: [
            { "orderable": false, "targets": [coloumnAction] } // Vô hiệu hóa sắp xếp cho cột 2, 4 và 5
    ],
    info: true,
    autoWidth: false,
    responsive: true,
    language: {
      search: "Tìm kiếm",
      info: "Bản ghi từ _START_ đến _END_ tổng cộng _TOTAL_ bản ghi",
      infoFiltered: "",
      zeroRecords: 'Không có dữ liệu',
      infoEmpty: "Hiện thị 0 đến 0 của 0 mục",
    }
  });

  //set width input search
  $('#table-crud_filter input').css('width', '250px');

  // Confirm delete
  $(document).on('click', '#delete__js', function(){
    let id = $(this).closest('tr').attr('id');
    let url = $(this).attr('url');
    Swal.fire({
      title: "Bạn có chắc muốn xóa?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'CÓ',
      cancelButtonText: 'KHÔNG',
    }).then((result) => {
      if (result.isConfirmed) {
        // Display loading
        $('#loading__js').css('display', 'flex');

        // Call api delete user
        $.ajax({
          url: url,
          type: 'POST',
          data: {
            id: id
          }
        }).done((response) => {
          // Hidden loading
          $('#loading__js').css('display', 'none');

          //If delete success
          if (response.status == 'success') {
            //Show success toast message 
            fire(toast, 'success', response.message)
            // Delete row in data table
            table.rows(`#${id}`).remove().draw();
          } else if (response.status == 'failed') {
            // Show error toast message 
            fire(toast, 'error', response.message)
          } else {
            // Show error toast message 
            fire(toast, 'error', response.message)
            // Reload page
            setTimeout(()=>{
              location.reload();
            }, 2000);
          }
        })
      }
    })
  });
});

// function init toast message
function toast() 
{
  return Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });
}

function fire(toast, type, message) 
{
  let background;
  let icon;
  if (type == 'success') {
    background = 'rgba(40,167,69,.85)';
    icon = 'success';
  } else if (type == 'error') {
    background = 'rgba(220,53,69,.85)';
    icon = 'error';
  }
  toast.fire({
    icon: icon,
    title: message,
    background: background,
    color: '#fff',
  })
}