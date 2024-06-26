

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
    axios.get($("#webservicePath").val() + `pessoas/list`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            id: $("#filter-id").val(),
            nome: $("#filter-nome").val(),
            tipoDocumento: $("#filter-tipoDocumento").val(),
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
                trHTML += '<td>' + item.nome + '</td>';
                trHTML += '<td>' + item.tipoDocumento + '</td>';
                trHTML += '<td>' + item.documento + '</td>';
        
                trHTML += `<td class="table-action">
                                <a href="painel-pessoas?id=` + item.id + `"><i class="align-middle" data-feather="eye"
                                        ></i></a>
                                <a href="#"><i class="align-middle" data-feather="edit"
                                        onclick="editItems(` + item.id + `)"></i></a>
                                <!--<a href="#"><i class="align-middle" data-feather="trash"
                                        onclick="deleteItems(` + item.id + `)"></i></a>-->
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
    axios.post($("#webservicePath").val() + `pessoas/create`, {
        
    status: 'ACTIVE',
    
    tipoDocumento: $("#form-create-tipoDocumento").val(),
    
    documento: $("#form-create-documento").val(),
    
    nome: $("#form-create-nome").val(),
    
    nomeFantasia: null,
    
    enderecoLogradouro: null,
    
    enderecoNumero: null,
    
    enderecoComplemento: null,
    
    enderecoCidade: null,
    
    enderecoEstado: null,
    
    enderecoPais: null,
    
    enderecoCep: null,
    
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

editItems = (id) => {
    setTimeout(() => {
        axios.get($("#webservicePath").val() + `pessoas/get`, {
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
                $("#form-edit-id").val(response.data.data.id)
                
                $("#form-edit-status").val(response.data.data.status)
                
                $("#form-edit-tipoDocumento").val(response.data.data.tipoDocumento)
                
                $("#form-edit-documento").val(response.data.data.documento)
                
                $("#form-edit-nome").val(response.data.data.nome)
                
                $("#form-edit-nomeFantasia").val(response.data.data.nomeFantasia)
                
                $("#form-edit-enderecoLogradouro").val(response.data.data.enderecoLogradouro)
                
                $("#form-edit-enderecoNumero").val(response.data.data.enderecoNumero)
                
                $("#form-edit-enderecoComplemento").val(response.data.data.enderecoComplemento)
                
                $("#form-edit-enderecoCidade").val(response.data.data.enderecoCidade)
                
                $("#form-edit-enderecoEstado").val(response.data.data.enderecoEstado)
                
                $("#form-edit-enderecoPais").val(response.data.data.enderecoPais)
                
                $("#form-edit-enderecoCep").val(response.data.data.enderecoCep)

                $("#form-edit-enderecoBairro").val(response.data.data.enderecoBairro)
    
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
    axios.put($("#webservicePath").val() + `pessoas/update`, {
        id: $("#form-edit-id").val(),
        
        status: $("#form-edit-status").val(),
        
        tipoDocumento: $("#form-edit-tipoDocumento").val(),
        
        documento: $("#form-edit-documento").val(),
        
        nome: $("#form-edit-nome").val(),
        
        nomeFantasia: $("#form-edit-nomeFantasia").val(),
        
        enderecoLogradouro: $("#form-edit-enderecoLogradouro").val(),
        
        enderecoNumero: $("#form-edit-enderecoNumero").val(),
        
        enderecoComplemento: $("#form-edit-enderecoComplemento").val(),
        
        enderecoCidade: $("#form-edit-enderecoCidade").val(),
        
        enderecoEstado: $("#form-edit-enderecoEstado").val(),
        
        enderecoPais: $("#form-edit-enderecoPais").val(),
        
        enderecoCep: $("#form-edit-enderecoCep").val(),

        enderecoBairro: $("#form-edit-enderecoBairro").val(),

        enderecoRegiao: $("#form-edit-enderecoRegiao").val(),
    
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
    $("#form-delete-id").val(id)
    $('#delete-items').modal('show')
}

destroyItems = () => {
    axios.delete($("#webservicePath").val() + `pessoas/delete`, { 
        params: {
            id: $("#form-delete-id").val()
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

extrairPlanilhaDePessoas = () => {
    $("#pessoasSheetButton").prop("disabled", true);
    $("#pessoasSheetButton").html("Aguarde...");
    axios.get($("#webservicePath").val() + `pessoas/sheet`, {
        params: {
            id: 1
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        },
        responseType: 'blob'
    }).then((response) => {
        if (response.data.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Falha ao renderizar planilha!',
            })
        } else {
            // create file link in browser's memory
            const href = URL.createObjectURL(response.data);

            // create "a" HTML element with href to file & click
            const link = document.createElement('a');
            link.href = href;
            link.setAttribute('download', 'Clientes e fornecedores.xlsx'); //or any other extension
            document.body.appendChild(link);
            link.click();

            // clean up "a" element & remove ObjectURL
            document.body.removeChild(link);
            URL.revokeObjectURL(href);
        }
        setTimeout(() => {
            $("#pessoasSheetButton").prop("disabled", false);
            $("#pessoasSheetButton").html("Extrair planilha");
        }, 3000);
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao renderizar planilha!',
        })
        console.log(error)
        setTimeout(() => {
            $("#pessoasSheetButton").prop("disabled", false);
            $("#pessoasSheetButton").html("Extrair planilha");
        }, 1500);
    });
}

