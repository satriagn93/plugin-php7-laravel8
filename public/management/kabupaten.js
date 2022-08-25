$(document).ready(function(){
    $("#provinsiIndex").on("change", function () {
        provinsiIndex();
    });

    function provinsiIndex() {
        var provinsi = $("#provinsiIndex").val();
        if (provinsi) {
            $.ajax({
                url: "/kabupaten/" + provinsi,
                beforeSend: function (request) {
                    $("#kabupatenIndex option").remove();
                    $("#kabupatenIndex").append(
                        '<option value="">Tunggu sebentar ...</option>'
                    );
                },
                success: function (data) {
                    if (data.success) {
                        let option = '<option value="{id}">{name}</option>';

                        $("#kabupatenIndex option").remove();
                        data.kabupaten.forEach(function (item) {
                            $("#kabupatenIndex").append(
                                option
                                    .replace(/{id}/g, item.id)
                                    .replace(/{name}/g, item.name)
                            );
                        });
                    } else {
                        $("#kabupatenIndex").val("").trigger("change");
                    }
                },
            });
        } else {
            $("#kabupatenIndex").val("").trigger("change");
        }
    }
    
    var getFilter = function() {
        return {
          'kabupatenIndex': $('#kabupatenIndex').val(),
        }
      }
  
    var btnSearch = $('#tampilkecamatan')
        btnSearch.on('click', function() {
        dataFilter.draw()
    })

    window.dataFilter = $('#tbkecamatan').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
          url: "/getkecamatan/",
          data: function(data) {
            data.filters = getFilter()
          }
        },
        columns: [{
            data: 'DT_RowIndex',
            'orderable': false,
            'searchable': false,
            class: 'text-center'
          },
          {
            data: 'id',
            name: 'id',
            class: "text-left",
            'orderable': false,
            'searchable': true
          },
          {
            data: 'name',
            name: 'name',
            'orderable': false,
            'searchable': true,
            class: "text-left"
          }
        ]
      });

});