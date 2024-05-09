$(document).ready(function () {
    //Carregar tabela
    listDatatable()
})

listDatatable = (currentPage = 1) => {

    //Limpar tabela
    $("#datatable-body").empty();
    $("#datatable-error").empty();
    $("#datatable-pagination").empty();

    //Carregando tabela
    var trHTML = '<p class="text-center">Carregando tabela...</p>';
    $('#datatable-error').append(trHTML);

    //Buscar dados
    axios.get($("#endPoint").val() + `log/list`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            code: $("#filter-code").val(),
            tag: $("#filter-tag").val()
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0 && typeof response.data.data == 'undefined') {
            $("#datatable-error").empty();
            var trHTML = '<p class="text-center">Nenhum resultado encontrado</p>';
            $('#datatable-error').append(trHTML);
        } else if (response.data.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Falha ao carregar tabela',
            })
        } else {
            //Renderiza os dados da tabela  
            var trHTML = '';
            $.each(response.data.data, function (i, item) {
                trHTML += '<tr>';
                trHTML += '<td>' + item.id + '</td>';
                trHTML += '<td>' + item.code + '</td>';
                trHTML += '<td>' + item.tag + '</td>';
                trHTML += '<td>' + item.endpoint + '</td>';
                trHTML += '<td>' + item.method + '</td>';
                trHTML += '<td>' + item.createdAt + '</td>';
        
                trHTML += `<td class="table-action">
                                <a href="#"><i class="align-middle" data-feather="eye"
                                        onclick="readItems(` + item.id + `)"></i></a>
                            </td>`;
                trHTML += '</tr>';
            });
            $('#datatable-body').append(trHTML);
            $("#datatable-error").empty();
            feather.replace();

            //Atualiza a paginação
            var pagination = '';
            var numberOfPages = response.data.numberOfPages;
            var disabledFirstPage = null;
            var disabledLastPage = null;
            if(currentPage == 1){
                disabledFirstPage = 'disabled';
            }
            if(currentPage == numberOfPages){
                disabledLastPage = 'disabled';
            }
            pagination += '<ul class="pagination justify-content-center">';
            pagination += '<li class="page-item"><a class="page-link '+disabledFirstPage+'" href="#" onClick="listDatatable(1)">Primeira</a></li>';
            if(currentPage == numberOfPages && currentPage - 2 > 0){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage - 2)+')">'+(currentPage - 2)+'</a></li>';
            }
            if(currentPage - 1 > 0){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage - 1)+')">'+(currentPage - 1)+'</a></li>';
            }
            pagination += '<li class="page-item active"><a class="page-link" href="#" onClick="listDatatable('+currentPage+')">'+currentPage+'</a></li>';
            if(currentPage + 1 <= numberOfPages){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage + 1)+')">'+(currentPage + 1)+'</a></li>';
            }
            if(currentPage == 1 && currentPage + 2 <= numberOfPages){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage + 2)+')">'+(currentPage + 2)+'</a></li>';
            }
            pagination += '<li class="page-item"><a class="page-link '+disabledLastPage+'" href="#" onClick="listDatatable('+numberOfPages+')">Última</a></li>';
            pagination += '</ul>';
            $('#datatable-pagination').append(pagination);
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao carregar tabela',
        })
    });
    
}

readItems = (id) => {
    axios.get($("#endPoint").val() + `log/get`, {
        params: {
            id: id
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Algo deu errado!',
            })
        } else {
            $("#form-view-id").val(response.data.data.id)

            $("#form-view-code").val(response.data.data.code)
            
            $("#form-view-tag").val(response.data.data.tag)
            
            $("#form-view-endpoint").val(response.data.data.endpoint)
            
            $("#form-view-method").val(response.data.data.method)
            
            $("#form-view-request").val(response.data.data.request)
            
            $("#form-view-response").val(response.data.data.response)
            
            $("#form-view-description").val(response.data.data.description)
            
            $("#form-view-userId").val(response.data.data.username)

            $("#form-view-createdAt").val(response.data.data.createdAt)
    
            $("#view-items").modal("show")
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Algo deu errado!',
        })
    });
}