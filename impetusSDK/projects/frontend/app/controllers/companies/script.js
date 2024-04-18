$(document).ready(function () {
    listDatatable()
})

listDatatable = (currentPage = 1) => {
    console.log("Carregando tabela...")

    //Limpar tabela
    $("#datatable-body").empty();
    $("#datatable-error").empty();
    $("#datatable-pagination").empty();

    //Carregando tabela
    var trHTML = '<p class="text-center">Carregando tabela...</p>';
    $('#datatable-error').append(trHTML);

    //Buscar dados
    axios.get($("#endPoint").val() + `companies/list`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            corporateName: $('#filter-corporateName').val(),
            status: $('#filter-status').val(),
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
            console.log(response.data)
            //Renderiza os dados da tabela  
            var trHTML = '';
            $.each(response.data.data, function (i, item) {
                trHTML += '<tr>';
                trHTML += '<td>' + item.corporateName + '</td>';
                trHTML += '<td>' + item.name + '</td>';
                trHTML += '<td>' + item.document + '</td>';
                trHTML += `<td class="table-action">
                                <a href="#"><i class="align-middle" data-feather="eye"
                                        onclick="readItems(` + item.id + `)"></i></a>
                                <a href="#"><i class="align-middle" data-feather="edit"
                                        onclick="editItems(` + item.id + `)"></i></a>
                                <a href="#"><i class="align-middle" data-feather="trash"
                                        onclick="deleteItems(` + item.id + `)"></i></a>
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

createItems = () => {
    $(".form-control").val(null)
    $('#create-items').modal('show')
}

storeItems = () => {
    axios.post($("#endPoint").val() + `companies/create`, {
        corporateName: $("#form-create-item-corporateName").val(),
        name: $("#form-create-item-name").val(),
        document: $("#form-create-item-document").val(),
        status: 'ACTIVE'
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#create-items').modal('hide')
            listDatatable()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}

readItems = (id) => {
    axios.get($("#endPoint").val() + `companies/get`, {
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
            $("#form-view-item-corporateName").val(response.data.data.corporateName)
            $("#form-view-item-name").val(response.data.data.name)
            $("#form-view-item-document").val(response.data.data.document)
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

editItems = (id) => {
    setTimeout(() => {
        axios.get($("#endPoint").val() + `companies/get`, {
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
                $("#form-edit-item-id").val(response.data.data.id)
                $("#form-edit-item-corporateName").val(response.data.data.corporateName)
                $("#form-edit-item-name").val(response.data.data.name)
                $("#form-edit-item-document").val(response.data.data.document)
                $("#form-edit-item-status").val(response.data.data.status)
                $("#edit-items").modal("show")
            }
        }).catch((error) => {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Algo deu errado!',
            })
        });
    }, 100);
}

updateItems = () => {
    axios.put($("#endPoint").val() + `companies/update`, {
        id: $("#form-edit-item-id").val(),
        corporateName: $("#form-edit-item-corporateName").val(),
        name: $("#form-edit-item-name").val(),
        document: $("#form-edit-item-document").val(),
        status: $("#form-edit-item-status").val(),
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#edit-items').modal('hide')
            listDatatable()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}

deleteItems = (id) => {
    $("#form-delete-item-id").val(id)
    $('#delete-items').modal('show')
}

destroyItems = () => {
    axios.delete($("#endPoint").val() + `companies/delete`, { 
        params: {
            id: $("#form-delete-item-id").val()
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#delete-items').modal('hide')
            listDatatable()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}