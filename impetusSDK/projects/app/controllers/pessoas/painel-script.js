$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const queryParamId = urlParams.get('id');
    readItems(queryParamId)
})

readItems = (id) => {
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
            $("#page-title").html(response.data.data.nome + " (" + response.data.data.documento + ")")

            $("#form-create-entidadeId").val(response.data.data.id)

            $("#form-create-arquivoId").val(response.data.data.id)

            $("#form-view-id").val(response.data.data.id)
            
            $("#form-view-status").val(response.data.data.status)
            
            $("#form-view-tipoDocumento").val(response.data.data.tipoDocumento)
            
            $("#form-view-documento").val(response.data.data.documento)
            
            $("#form-view-nome").val(response.data.data.nome)
            
            $("#form-view-nomeFantasia").val(response.data.data.nomeFantasia)
            
            $("#form-view-enderecoLogradouro").val(response.data.data.enderecoLogradouro)
            
            $("#form-view-enderecoNumero").val(response.data.data.enderecoNumero)
            
            $("#form-view-enderecoComplemento").val(response.data.data.enderecoComplemento)
            
            $("#form-view-enderecoCidade").val(response.data.data.enderecoCidade)
            
            $("#form-view-enderecoEstado").val(response.data.data.enderecoEstado)
            
            $("#form-view-enderecoPais").val(response.data.data.enderecoPais)
            
            $("#form-view-enderecoCep").val(response.data.data.enderecoCep)

            $("#form-view-enderecoBairro").val(response.data.data.enderecoBairro)

            $("#form-view-enderecoRegiao").val(response.data.data.enderecoRegiao)
            
            $("#form-view-fonte").val(response.data.data.fonte)
            
            $("#form-view-homologado").val(response.data.data.homologado)
            
            $("#form-view-categoria").val(response.data.data.categoria)
            
            $("#form-view-categoriaFornecedor").val(response.data.data.categoriaFornecedor)

            if(response.data.data.tipoDocumento == "CPF"){
                $("#show-nomeFantasia").attr("hidden",true);
                $("#show-categoriaFornecedor").attr("hidden",true);
                $("#show-homologado").attr("hidden",true);
            }
    
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Cliente/fornecedor não encontrado',
            confirmButtonText: "Voltar",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $(location).prop('href', 'pessoas')
            } else if (result.isDenied) {
                $(location).prop('href', 'pessoas')
            }
          });
    });
}

imprimir = () => {
    $("#pessoasPrintButton").prop("disabled", true);
    $("#pessoasPrintButton").html("Aguarde...");
    axios.get($("#webservicePath").val() + `pessoas/print`, {
        params: {
            id: $("#form-view-id").val()
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
                text: 'Falha ao renderizar proposta!',
            })
        } else {
            let responseHtml = response.data;

            //Open on a new tab
            const file = new Blob([response.data], { type: 'application/pdf' }) 
            const fileURL = URL.createObjectURL(file)
            window.open(fileURL)

            //Download com a library download.js
            //const content = response.headers['content-type'];
            //download(responseHtml, "Proposta.pdf", content)
        }
        $("#pessoasPrintButton").prop("disabled", false);
        $("#pessoasPrintButton").html("Imprimir proposta");
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao renderizar proposta!',
        })
        $("#pessoasPrintButton").prop("disabled", false);
        $("#pessoasPrintButton").html("Imprimir proposta");
    });
}