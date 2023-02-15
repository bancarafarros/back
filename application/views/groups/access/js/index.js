window.addEventListener('gcrud.datagrid.ready', () => {
  $(".gc-header-tools").append(
    ` <div class="floatR l5 mr-1">
        <button id="btn-sinkronisasi" class="btn btn-default btn-outline-dark t5">
          <i class="fa fa-sync floatL t3"></i>
          <span class="hidden-xs floatL l5">Sinkronisasi</span>
          <div class="clear">
          </div>
        </button>
      </div>`
  );

  $("#btn-sinkronisasi").click(function (e) {
    e.preventDefault();
    showLoading()

    $.ajax({
      type: "post",
      url: `${baseUrl}/api/Sinkronisasi/users?tipe=4`,
      data: "data",
      dataType: "json",
      success: function (response) {
        $('.fa-refresh').trigger('click');
        hideLoading()

        Swal.fire({
          title: 'Berhasil sinkronisasi data Mobilizer',
          icon: 'success',
          confirmButtonColor: '#3ab50d',
        }).then((result) => {

        })

      },
      error: function (xhr, status, error) {
        var errorMessage = xhr.status + ': ' + xhr.statusText
        alert('Error - ' + errorMessage);

        $('.fa-refresh').trigger('click');
        hideLoading()

        Swal.fire({
          title: 'Gagal sinkronisasi data Mobilizer',
          icon: 'error',
          confirmButtonColor: '#3ab50d',
        }).then((result) => {

        })

      }
    });

  });

});