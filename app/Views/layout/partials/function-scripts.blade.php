<script>

  $(document).ready(function(){
    $("input[data-provide='datepicker']").datepicker({
      language: 'id',
    });
    $('#loader').fadeOut();
    $('.error-message').hide();
    var refreshTime = 5000; // in milliseconds, so 10 minutes
    // window.setInterval( function() {
    //     var url = window.location.href;
    //     $.get( url );
    // }, refreshTime );
  });



  $.skylo({

    // Info, Success, Warning, Danger
    state: 'info',

    inchSpeed: 200,

    // Range 1 - 100.
    initialBurst: 5,

    // Flat style loading bar
    flat: false

  });

  function diffMonth(YM1,YM2) {
    yearMonth1 = new Date(YM1);
    yearMonth2 = new Date(YM2);
    return (
      yearMonth2.getMonth() -
      yearMonth1.getMonth() +
      12 * (yearMonth2.getFullYear() - yearMonth1.getFullYear()) +1
    );
  }

  function randomString(length) {
    var result           = '';
    var characters       = 'abcdefghijklmnopqrstuvwxyz';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
  }
  function Go(link, menu, type, name ='', parameter = ''){  
    $.ajax({
        url: link+"/"+type,
        type: 'POST',
        dataType: 'html',
        data :  { 
            "{{csrf_token()}}": "{{csrf_hash()}}",
            "parameter"       : parameter,
        },
        beforeSend : function() {
            $(".data-loader").fadeIn();
            $("#content").slideUp();
            $.skylo('start');
        },
        success: function(res) {
            $('#content').html(res);
            selectbox();
            $("#activeBreadcrumb").removeClass("active");
            $("#activeBreadcrumb").html(`<a href='javascript:void(0)' onclick='Go("`+link+`", "`+menu+`", "res")'>`+menu+`</a>`);
            $("#appendBreadcrumb").remove();
            if(name != '') {
              setTimeout(function() { 
                  $("#breadcrumb").append('<li class="breadcrumb-item active" id="appendBreadcrumb">'+name+'</li>');
              }, 10);
            }
            $("#content").slideDown();
            if(parameter != ''){
              $('#parameter').val(parameter);
            }
        },
        complete : function() {
            $(".data-loader").fadeOut();
            $.skylo('end');
        }
    });
  }
  
  $( '.price' ).mask('#.##0', {reverse: true});
  $('.datepicker-autoclose').datepicker({
    autoclose: true
  });

  function selectbox(modal='') {
    if($(".selectbox").not('.selectbox-search')) {
      if(modal != '') {
        $(".selectbox").select2({
          minimumResultsForSearch: Infinity,
          width                  : '100%',
          dropdownParent         : $('#'+modal),
        });
      } else {
        $(".selectbox").select2({
          minimumResultsForSearch: Infinity,
          width                  : '100%'
        });
      }
    }
    if($(".selectbox").hasClass('selectbox-search')) {
      if(modal != '') {
        $(".selectbox-search").select2({
          width         : '100%',
          dropdownParent: $('#'+modal)
        });
      } else {
        $(".selectbox-search").select2({
          width: '100%'
        });
      }
    } 
  }

  function only_number(){
    $('.only_number').on('change keyup', function() {
      var sanitized = $(this).val().replace(/[^0-9]/g, '');
      $(this).val(sanitized);
    });
  }

  function msg(title,icon,data=''){
    Swal.fire({
        title : title,
        icon : icon,  // info, success,  warning, error
        html : data
      });
  }

  function check_token(type_token,id_user){
    $("#token_type").val(type_token);
    $("#token_user").val(id_user);
    var btn_onclick = 'send_token('+type_token+',"{{session('session_id')}}","1")';
    $("#btn_token_send_again").attr('onclick',btn_onclick);
    $.ajax({
        url : '{{base_url()}}/token/check',
        type : "post",
        dataType : 'json',
        data : {
            "{{csrf_token()}}" : "{{csrf_hash()}}",
            "token" : type_token,
            "id"    : id_user,
        },
        success : function(data) {
          if(data.status=="NOK"){
            send_token(type_token,id_user);
          }
        }
    });
  }

  function send_token(type_token,id_user,repeat=''){
  if(repeat=='1'){
      $(".loader").show();
  } 
  $.ajax({
        url : '{{base_url()}}/token/request',
        type : "post",
        data : {
            "{{csrf_token()}}" : "{{csrf_hash()}}",
            "token" : type_token,
            "id"    : id_user,
        },
        success : function(data) {
            if(repeat=='1'){
                $(".loader").hide();
                msg('Berhasil','success','Silahkan periksa email anda');
            }
        },
        error: function () {
            $(".loader").hide();
        },
    });
  }

  function action_no_confirm(form_id,url,callback) {
    $.ajax({
          url : url,
          type : "post",
          data : new FormData($("#" + form_id)[0]),
          contentType : false,
          processData : false,
          beforeSend : function() {
            $(".loader").show();
          },
          success : function(data) {
            if(data == "OK") {
              if(callback == "") {
                location.reload();
              } else {
                callback();
              }
            } else {
              Swal.fire({
                title : 'Terjadi Kesalahan',
                html : data,
                icon : 'error' 
              })
            }
          },
          complete : function() {
            $(".loader").hide();
          }
        });
  }

  function action(form_id, title, text, icon, url, callback,typeAlert="") {
    Swal.fire({
      title: title,
      html: text,
      icon: icon,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      showLoaderOnConfirm: true,
    }).then((result) => {
      if(result.isConfirmed) {
        $.ajax({
          url : url,
          type : "post",
          dataType : 'json',
          data : new FormData($("#" + form_id)[0]),
          contentType : false,
          processData : false,
          beforeSend : function() {
            $(".loader").show();
          },
          success : function(res) {
             if(res.status == "OK") {
                $(".modal").modal('hide'); 
                if(callback == "") {
                  Swal.fire({
                    title: "Success",
                    icon: "success",
                    }).then(function (result) {
                        location.reload();
                    });   
                } else {
                  if(typeAlert=="toast") {
                    $('.toast').toast('show');
                    $('.toast').addClass('bg-success bg-soft');
                    $('.toast-message').text(res.message);
                    callback();
                  } else {
                    Swal.fire({
                    title: "Success",
                    icon: "success",
                    }).then(function (result) {
                        callback();
                    }); 
                  }
                  
                }
              } else {
                if(typeAlert=="toast") {
                  $('.toast').toast('show');
                  $('.toast').addClass('bg-danger bg-soft');
                  var errorList = res.message;
                  let errorText = "";
                  for (var key in errorList) {
                    errorText += errorList[key] + "<br>";
                  }
                  $('.toast-message').html(`
                    <ul>
                      <li>` + errorText + `</li>
                    </ul>
                  `);
                }
                else {
                  if(res.title != '' || res.title != null || res.title != 0) {
                    var errMessage = res.title;
                  } else {
                    var errMessage = "Something wrong!";
                  }
                  Swal.fire({
                    title: errMessage,
                    html: res.message,
                    icon: 'error'
                  })
                }
              }

          },
          complete : function() {
            $(".loader").hide();
          }
        });
      }
    });
  }

  function action_delete(parameter, title, text, icon, url, callback, typeAlert="") {
    Swal.fire({
      title: title,
      html: text,
      icon: icon,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      showLoaderOnConfirm: true,
    }).then((result) => {
      if(result.isConfirmed) {
        $.ajax({
          url : url,
          type : "post",
          dataType : 'json',
          data : {
            id : parameter,
            "<?= csrf_token() ?>" : "<?= csrf_hash() ?>"
          },
          beforeSend : function() {
            $(".loader").show();
          },
          success: function(res) {
            if (res.status == "OK") {
              if (callback == "") {
                Swal.fire({
                  title: 'Success',
                  icon: 'success',
                }).then((result) => {
                  location.reload();
                })
              } else {
                if(typeAlert=="toast") {
                  toastr.options = {
                    "positionClass": "toast-top-full-width"
                  }
                  toastr.success("Success");
                } else {
                  Swal.fire("Success", "", "success");
                }
                callback();
              }
            } else {
              Swal.fire({
                title: 'Error',
                html: res.message,
                icon: 'error'
              })
            }
          },
          complete : function() {
            $(".loader").hide();
          }
        });
      }
    });
  }

  function approve_invitation(id, isApprove, title, message, icon, url, callback) {
    Swal.fire({
      title: title,
      text: message,
      icon: icon,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      showLoaderOnConfirm: true,
    }).then((result) => {
      if(result.isConfirmed) {
        $.ajax({
          url : url,
          type : "post",
          data : {
            parameter: id,
            is_approve: isApprove,
            "{{csrf_token()}}": "{{csrf_hash()}}"
          },
          beforeSend : function() {
            $(".loader").show();
          },
          success : function(data) {
            if(data == "OK") {
              if(callback == "") {
                location.reload();
              } else {
                callback();
              }
            } else {
              Swal.fire({
                title : 'Terjadi Kesalahan',
                html : data,
                icon : 'error' 
              })
            }
          },
          complete : function() {
            $(".loader").hide();
          }
        });
      }
    });
  }

  function read_notif(id_notif,link) {
    $.ajax({
      url: '{{base_url()}}/notification/read', 
      type: 'POST',
      data: {"{{csrf_token()}}" : "{{csrf_hash()}}",'id_parameter':id_notif},
      success: function(data) {
        window.open(link,'_self') 
      }
    });
  }

  function dosen_list(id, kode_unit='', modal=''){
    if(modal != '') {
      $("#"+id).select2({
        placeholder   : 'Pilih dosen',
        dropdownParent: $('#'+modal),
        width         : '100%',
        language      : {
          "noResults": function(){
              return "Data tidak ditemukan";
          },
          "errorLoading": function () {
              return "Terjadi kesalahan"
          },
        },
        ajax: { 
          url: '<?= base_url() ?>/karyawan/get_dosen',
          type: "post",
          dataType: 'json',
          delay: 250,
          data : function (params) {
            return {
              "{{csrf_token()}}": "{{csrf_hash()}}",
              term              : params.term || '',
              page              : params.page || 1,
              kode_unit         : kode_unit
            };
          },
          cache: true,
        },
        minimumInputLength: 0,
      });
    } else {
    $("#"+id).select2({
      width: '100%',
      placeholder: 'Pilih dosen',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url: '<?= base_url() ?>/karyawan/get_dosen',
        type: "post",
        dataType: 'json',
        delay: 250,
        data : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term              : params.term || '',
            page              : params.page || 1,
            kode_unit         : kode_unit
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
    }
  }

  function dosen_list_skpd(id, modal=''){
    if(modal != '') {
      $("#"+id).select2({
        placeholder   : 'Pilih dosen',
        dropdownParent: $('#'+modal),
        width         : '100%',
        language      : {
          "noResults": function(){
              return "Data tidak ditemukan";
          },
          "errorLoading": function () {
              return "Terjadi kesalahan"
          },
        },
        ajax: { 
          url: '<?= base_url() ?>/karyawan/get_dosen_skpd',
          type: "post",
          dataType: 'json',
          delay: 250,
          data : function (params) {
            return {
              "{{csrf_token()}}": "{{csrf_hash()}}",
              term              : params.term || '',
              page              : params.page || 1,
            };
          },
          cache: true,
        },
        minimumInputLength: 0,
      });
    } else {
    $("#"+id).select2({
      width: '100%',
      placeholder: 'Pilih dosen',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url: '<?= base_url() ?>/karyawan/get_dosen_skpd',
        type: "post",
        dataType: 'json',
        delay: 250,
        data : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term              : params.term || '',
            page              : params.page || 1,
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
    }
  }

  function fakultas_list(id){
    $("#"+id).select2({
      width: '100%',
      placeholder: 'Pilih fakultas',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url     : '<?= base_url() ?>/fakultas/get_fakultas',
        type    : "post",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term              : params.term || '',
            page              : params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
  }

  function matkul_list(id, kode_fakultas=''){
    $("#"+id).select2({
      width: '100%',
      placeholder: 'Pilih Mata Kuliah',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url     : '<?= base_url() ?>/matakuliah/get_matakuliah',
        type    : "post",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            'kode_fakultas': kode_fakultas,
            term: params.term || '',
            page: params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
  }

  function get_prodi(from_id,to_id,mdl='') {
    var nim = $("#"+from_id).val();
    $.ajax({
      url : "<?php echo base_url() ?>/prodi/get_mhs_prodi",
      type : 'post',
      dataType : 'json',
      data : {
        "{{csrf_token()}}" : "{{csrf_hash()}}",
        "nim" : nim
      },
      success : function(data) {
        if(mdl=='label'){
          $("#"+to_id).html(data.prodi);
        }
        else {
          $("#"+to_id).val(data.prodi);
        }
      }
    });
  }

  function get_dosen_prodi(from_id, to_id, lbl='') {
    var nip = $("#"+from_id).val();
    $.ajax({
      url : "<?php echo base_url() ?>/prodi/get_dosen_prodi",
      type : 'post',
      dataType : 'html',
      data : {
        "{{csrf_token()}}": "{{csrf_hash()}}",
        "nip"             : nip,
      },
      success : function(data) {
        if(lbl != '') {
          $("#"+to_id).html(data);
        } else {
          $("#"+to_id).val(data);
        }
      }
    });
  }

  function get_nidn(from_id, to_id, lbl='') {
    var nip = $("#"+from_id).val();
    $.ajax({
      url : "<?php echo base_url() ?>/karyawan/get_nidn",
      type : 'post',
      dataType : 'html',
      data : {
        "{{csrf_token()}}": "{{csrf_hash()}}",
        "nip"             : nip,
      },
      success : function(data) {
        if(lbl != '') {
          $("#"+to_id).html(data);
        } else {
          $("#"+to_id).val(data);
        }
      }
    });
  }

  function mahasiswa_list(id, modal=''){
    if(modal != '') {
    $("#"+id).select2({
      placeholder   : 'Pilih Mahasiswa',
      dropdownParent: $('#'+modal),
      width         : '100%',
      language      : {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url     : '<?= base_url() ?>/mahasiswa/get_mhs',
        type    : "post",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term: params.term || '',
            page: params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
    } else {
    $("#"+id).select2({
      placeholder: 'Pilih Mahasiswa',
      width: '100%',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      ajax: { 
        url     : '<?= base_url() ?>/mahasiswa/get_mhs',
        type    : "post",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term: params.term || '',
            page: params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
    }
  }

  function bidang_keilmuan_list(id) {
    $("#"+id).select2({
      placeholder: 'Pilih Bidang Keilmuan',
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        },
        "errorLoading": function () {
            return "Terjadi kesalahan"
        },
      },
      width: '100%',
      ajax: { 
        url     : '<?= base_url() ?>/bidang_keilmuan/get_bi',
        type    : "post",
        dataType: 'json',
        delay   : 250,
        data    : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            term: params.term || '',
            page: params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
  }

  function keywordTags(){
    if($('input').hasClass('tags-input')){
      $(".tags-input").tagsinput({
        confirmKeys: [13, 44]
      });
      $('input').on('keypress', function(e){
        if (e.keyCode == 13){
          e.keyCode = 44;
          e.preventDefault();
        }
      });
    }
  }

  function clearKeyword(){
    $('.tags-input').tagsinput('removeAll');
  }

  function logout() {
    window.open('{{base_url('management')}}/logout','_self')
  }

  function refresh_datatables() {
    $("#table").DataTable().ajax.reload();
  }

  function loading(){
    Swal.fire({
        title:'Loading',
        html: '<center><img src="{{base_url('')}}/assets/images/design/home_loader.gif"></center>',
        showCancelButton: false,
        showConfirmButton :false,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey:false,
      });
    $(".swal2-popup").css("width","250px");   
  }

  function getFilterData(filter_data, type=''){
    $("#filter_data").val('').trigger('change');
    $(".select-filter").select2({
      dropdownParent: $('#modal-filter'),
      placeholder: 'Pilih '+filter_data,
      language: {
        "noResults": function(){
            return "Data tidak ditemukan";
        }
      },
      ajax: { 
        url: 'get_filter_data',
        type: "post",
        dataType: 'json',
        delay: 250,
        data : function (params) {
          return {
            "{{csrf_token()}}": "{{csrf_hash()}}",
            "filter_data"     : filter_data,
            "type"            : type,
            term              : params.term || '',
            page              : params.page || 1
          };
        },
        cache: true,
      },
      minimumInputLength: 0,
    });
  }

  function rest_filter(restFn, e) {
    var filter_data = $('#form-filter input[type=radio][name=formRadios]:checked').val();
    $("#route_data").val(filter_data);
    e.preventDefault();
    $.ajax({
      url : "route_table",
      type : "post",
      data : new FormData($("#form-filter")[0]),
      contentType : false,
      processData : false,
      success : function(data) {
        eval(restFn+'(\"'+ filter_data +'\")');
        $("#modal-filter").modal('hide');
      },
    });
  }

  function check_filter(route='', name) {
    $.ajax({
      url     : "check_filter",
      type    : 'POST',
      dataType: 'html',
      data    : {
        "{{csrf_token()}}": "{{csrf_hash()}}",
        "route"           : route,
        "name"            : name
      },
      success : function(data) {
        if(route == 'fakultas') {
          $("#filter_fakultas").trigger('click');
        } else {
          $("#filter_dosen").trigger('click');
        }
        $("#filter_data").append(data);
      }
    });
  }


  function reset_filter(name) {
    $.ajax({
        url     : "reset_filter",
        type    : 'POST',
        dataType: 'html',
        data    : {
        "{{csrf_token()}}": "{{csrf_hash()}}",
        "name"            : name
        },
        success : function(data) {

          if (name == 'totalMahasiswa') {
            $("#filter_fakultas_mahasiswa").val('').trigger('change');
            $("#filter_status_mahasiswa").val('').trigger('change');
            $("#filter_tahunangkatan_mahasiswa").val('').trigger('change');
            totalMahasiswa();
            $("#filter-total_mhs").modal('hide');
          } else if (name == 'totalMahasiswaBaru') {
            $("#filter_fakultas_mahasiswa_baru").val('').trigger('change');
            totalMahasiswaBaru();
            $("#filter-total_mhs_baru").modal('hide');
          } else if (name == 'totalDosen') {
            $("#filter_jabatan_akademik_dosen").prop("checked", true);
            totalDosen("", 1);
            $("#filter-total_dosen").modal('hide');
          } else if (name == 'rasioDosenMahasiswa') {
            $("#filter_fakultas_rasio_dosen_mahasiswa").val('').trigger('change');
            rasioDosenMahasiswa();
            $("#filter-rasio_dosen_mhs").modal('hide');
          } else if (name == 'jumlahPenelitian') {
            $("#filter_fakultas_jumlah_penelitian").val('').trigger('change');
            chartJumlahPenelitian();
            jumlahPenelitian();
            $("#filter-jumlah_penelitian").modal('hide');
          } else if (name == 'jumlahPengabdian') {
            $("#filter_fakultas_jumlah_pengabdian").val('').trigger('change');
            chartJumlahPengabdian();
            jumlahPengabdian();
            $("#filter-jumlah_pengabdian").modal('hide');
          } else if (name == 'marketingkompetitor') {
            $("#filter-competitor_name").val("").trigger("change");
            $("#filter-competitor_program").val("").trigger("change");
            $("#filter-competitor_year").val("").trigger("change");
            chartMarketingKompetitor();
            $("#filter-markom").modal('hide');
          } else if (name == 'jenisbeasiswa') {
            $("#filter-beasiswa_code").val("").trigger("change");
            $("#filter-beasiswa_year").val("").trigger("change");
            chartJenisBeasiswa();
            $("#modal-jenisBeasiswa").modal('hide');
          } else if (name == 'sumberdanabeasiswa') {
            $("#filter-donatur_name").val("").trigger("change");
            $("#filter-donatur_year").val("").trigger("change");
            chartSumberDana();
            $("#modal-sumberdanaBeasiswa").modal('hide');
          }
          
        }
    });
  }

  function reset_compare(name) {
    $.ajax({
      url     : "reset_compare",
      type    : 'POST',
      dataType: 'html',
      data    : {
        "{{csrf_token()}}": "{{csrf_hash()}}",
        "name"            : name
      },
      success : function(data) {

        if (name == 'totalMahasiswa') {
          $("#compare_fakultas_mahasiswa").val('').trigger('change');
          $("#compare_tahun_mahasiswa").val('').trigger('change');
          compareTotalMahasiswa();
          $("#compare-total_mhs").modal('hide');
        }
        
      }
    });
  }

  function reset_global_compare() {
    $.ajax({
      url     : "reset_global_compare",
      type    : 'POST',
      dataType: 'html',
      data    : {
        "{{csrf_token()}}": "{{csrf_hash()}}"
      },
      success : function(data) {

        $("#compare_fakultas_global").val("").trigger("change");
        $("#compare_prodi_global").val("").trigger("change");
        $("#compare_tahunangkatan_global").val("").trigger("change");
        $("#compare_tahun_global").val("").trigger("change");
        $("#compare_bulan_global").val("").trigger("change");
        compareTotalMahasiswa();
        compareTotalMahasiswaBaru();
        compareTotalDosen();
        compareRasioDosenMahasiswa();

        $("#globalModal").modal('hide');
        
      }
    });
  }

  function reset_global_filter() {
    $.ajax({
      url     : "reset_global_filter",
      type    : 'POST',
      dataType: 'html',
      data    : {
        "{{csrf_token()}}": "{{csrf_hash()}}"
      },
      success : function(data) {

        $("#filter_fakultas_global").val("").trigger("change");
        $("#filter_status_global").val("").trigger("change");
        $("#filter_tahunangkatan_global").val("").trigger("change");
        $("#filter_tahun_global").val("").trigger("change");
        $("#filter_bulan_global").val("").trigger("change");
        totalMahasiswa();
        totalMahasiswaBaru();
        totalDosen("", 1);
        rasioDosenMahasiswa();

        $("#globalModal").modal('hide');
        
      }
    });
  }

  function yearmonthpickerModal(id,modalID){
     $("#"+id).datepicker( {
      format: "yyyy-mm",
      viewMode: "months", 
      minViewMode: "months",
      autoclose : true,
      container: '#'+modalID
    });
  }

  function yearpickerModal(id,modalID){
     $("#"+id).datepicker( {
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      autoclose : true,
      container: '#'+modalID
    });
  }
 
</script>