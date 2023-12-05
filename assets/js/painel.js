

function cadGeral(formId, modalId, pageAcao, pageRetorno) {
    $('#' + formId).on('submit', function (k) {
        k.preventDefault();

        var formdata = $(this).serializeArray();
        formdata.push(
            { name: "acao", value: pageAcao },
        );

        $.ajax({
            type: "POST",
            dataType: 'html',
            url: 'controle.php',
            data: formdata,
            beforeSend: function (retorno) {
            }, success: function (retorno) {
                console.log(retorno);
                $('#' + modalId).modal('hide');
                setTimeout(function () {
                    atualizarPagina(pageRetorno);
                }, 1000);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Salvo com Sucesso',
                    showConfirmButton: false,
                    timer: 1500

                })


            }
        });
    })
}


function atualizarPagina(dataMenu) {
    var dados = {
        acao: dataMenu
    }
    $.ajax({
        type: "POST",
        dataType: 'html',
        url: 'controle.php',
        data: dados,
        beforeSend: function () {

        }, success: function (e) {

            $('div#showpage').html(e);
        }
    })


}



$(document).ready(function () {

    // fazer as mascars funcionarem
    // masks();

    $('#modalAddCliente').on('shown.bs.modal', function () {
        $('input#nomeCliente').trigger('focus')
    });

    $('#modalAltCliente').on('shown.bs.modal', function () {
        $('input#nomeClienteAlt').trigger('focus')
    });


    // msgDelete();


    // navegação na página 
    // menu que vai para o lado ao clicar nas opções
    $('.linkMenu').click(function (event) {
        event.preventDefault();

        let menuClicado = $(this).attr('idMenu');

        let dados = {
            acao: menuClicado,
        };

        var menuToggle = document.getElementById('controle-menu-toggle');

        var clockToggle = document.getElementById('controle-clock-toggle');

        var clockNavToggle = document.getElementById('clock-nav');

        $.ajax({
            type: "POST",
            dataType: 'html',
            url: 'controle.php',
            data: dados,
            beforeSend: function () {
                // loading();
            }, success: function (retorno) {
                if (retorno != 'Home') {
                    setTimeout(function () {
                        // loadingEnd();
                        $('div#showpage').html(retorno);
                        if (!menuToggle.classList.contains("menu-lado")) {
                            menuToggle.classList.toggle("menu-lado");
                        };

                        if (!clockToggle.classList.contains("clocka")) {
                            clockToggle.classList.toggle("clocka");
                        };

                        if (!clockNavToggle.classList.contains("clock-son")) {
                            clockNavToggle.classList.toggle("clock-son");
                        };
                    }, 300);
                } else if (retorno == 'Home') {
                    location.reload();

                } else {
                    msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                }

            }
        });
    });



})



// função de loading a página

function loading() {
    Swal.fire({
        title: 'Carregando...',
        html: 'Aguarde um momento.',
        didOpen: () => {
            Swal.showLoading()
        }
    })

}

function loadingEnd() {
    Swal.close();
}

function listarPage(listar) {

    let dados = {
        acao: listar,
    };

    $.ajax({
        type: "POST",
        dataType: 'html',
        url: 'controle.php',
        data: dados,
        beforeSend: function () {
        }, success: function (retorno) {
            $('div.footer-father-f-f').html(retorno);
        }
    });
}


// funções de mask
function masks() {
    $('.maskTelefone').inputmask({
        mask: '(99) 9 9999-9999'
    });

    $('.maskCPF').inputmask({
        mask: '999.999.999-99'
    });


    // ABENÇOADO SEJA ESTE LINK 
    // https://stackoverflow.com/questions/35413377/jquery-mask-number-with-commas-and-decimal 


    $('.maskValor').mask("#,##0.00", { reverse: true });

}




// FAZER SESSÃO DE LOGIN

// funções de add 

function Login() {

    // console.log('botao');

    $('#formLogin').submit(function (event) {
        event.preventDefault();

        let form = this;

        let dadosForm = $(this).serializeArray();

        dadosForm.push(
            { name: 'acao', value: 'loginEntrar' },
        )
        // console.log(dadosForm);

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'controle.php',
            data: dadosForm,
            beforeSend: function () {

            },
            success: function (retorno) {

                console.log(retorno);

                if (retorno === 'OK') {

                    msgGeral('Login efetuado com sucesso!', 'success');
                    form.reset();

                    listarPage('Home');

                    setTimeout(function () {


                        window.location.reload();

                    }, 1000);
                } else {
                    msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                }

            }

        });

    });

}


// DESLOGAR DA SESSÃO

// para chamar o sweet alert, tem que usar a função primeiro para poder colocar ela no código
// desde que tenha o onclick

function LoginSair() {

    // console.log('botao');
    // mensagem editar, olhar documentação do sweet alert
    Swal.fire({
        title: "Você tem certeza?",
        text: "Essa ação irá te deslogar!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#582db6',
        // procurar sobre sweet alert bordas
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não, cancelar!',
        confirmButtonText: 'Sim, sair!'
    }).then((result) => {
        if (result.isConfirmed) {

            var dados = {
                acao: 'loginSair'
            };

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'controle.php',
                data: dados,
                beforeSend: function () {

                }, success: function (retorno) {

                    if (retorno === 'OK') {
                        Swal.fire({
                            title: 'Desconectado!',
                            text: 'Você deslogou com sucesso.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500

                        })
                        setTimeout(function () {
                            window.location.reload();
                        }, 600);
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: retorno,
                            icon: 'error',
                            showConfirmButton: true,
                            timer: 300
                        })
                    }

                }
            });
        }
    })

}



// function masks2() {
//     $('.maskValor').inputmask({
//         mask: '(99) 9 9999-9999'
//     });
// }


// mensagem editar
function msgGeral(msg, tipo) {
    Swal.fire({
        position: 'center',
        icon: tipo,
        title: msg,
        showConfirmButton: false,
        timer: 1500
    });
}





// funções de add 

var sendAddCliente = false;

function addCliente() {

    // console.log('botao');

    if (!sendAddCliente) {

        $('#frmAddCliente').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'addCliente' },
            )

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAddCliente').modal('hide');
                        setTimeout(function () {
                            msgGeral('Cadastro efetuado com sucesso!', 'success');
                            listarPage('listarCliente');
                            form.reset();
                        }, 500);
                    } else {
                        listarPage('listarCliente');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAddCliente = true;

        return;

    } else {

        return;

    }

}


var sendAddTipoPag = false;

function addTipoPag() {

    // console.log('botao');

    if (!sendAddTipoPag) {

        $('#frmAddTipoPag').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'addTipoPag' },
            )

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAddTipoPag').modal('hide');
                        setTimeout(function () {
                            msgGeral('Cadastro efetuado com sucesso!', 'success');
                            listarPage('listarTipoPag');
                            form.reset();
                        }, 500);
                    } else {
                        listarPage('listarTipoPag');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAddTipoPag = true;

        return;

    } else {

        return;

    }

}


var sendAddPag = false;

function addPag() {

    // console.log('botao');

    if (!sendAddPag) {

        $('#frmAddPag').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'addPag' },
            )

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAddPag').modal('hide');
                        setTimeout(function () {
                            msgGeral('Cadastro efetuado com sucesso!', 'success');
                            listarPage('listarPag');
                            form.reset();
                        }, 500);
                    } else {
                        listarPage('listarPag');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAddPag = true;

        return;

    } else {

        return;

    }

}


// função de adicionar item na dashboard
var sendAddProduto = false;

function addProduto() {

    // console.log('botao');

    if (!sendAddProduto) {

        $('#frmAddProduto').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'addProduto' },
            )

            // var dados = {
            //     acao: 'addCliente',
            // }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAddProduto').modal('hide');

                        msgGeral('Cadastro efetuado com sucesso!', 'success');
                        listarPage('listarProduto');
                        form.reset();
                    } else {
                        listarPage('listarProduto');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAddProduto = true;

        return;

    } else {

        return;

    }

}





// funções de alterar

function dataCliente(id, modal) {

    var dados = {
        acao: 'dataCliente',
        id: id,
    };

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: 'controle.php',
        data: dados,
        beforeSend: function () {

        }, success: function (retorno) {

            var status = retorno.status;

            if (status === 'OK') {
                $('#' + modal).modal('show');
                $('input#nomeUsuarioAlt').val(retorno.dadosArray['nome']);
                $('input#emailUsuarioAlt').val(retorno.dadosArray['email']);
                $('input#cpfUsuarioAlt').val(retorno.dadosArray['cpf']);
                $('input#senhaUsuarioAlt').val(retorno.dadosArray['senha']);
                $('input#inputAltCliente').val(id);
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: retorno,
                    icon: 'error',
                    showConfirmButton: true,
                })
            }

        }
    });

}

var sendAltCliente = false;

function altCliente() {

    // console.log('botao');

    if (!sendAltCliente) {

        $('#frmAltCliente').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'altCliente' },
            )

            // var dados = {
            //     acao: 'addCliente',
            // }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAltCliente').modal('hide');
                        setTimeout(function () {
                            msgGeral('Alteração efetuada com sucesso!', 'success');
                            listarPage('listarCliente');
                            form.reset();
                        }, 500);
                    } else {
                        listarPage('listarCliente');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAltCliente = true;

        return;

    } else {

        return;

    }

}


function dataTipoPag(id, modal) {

    var dados = {
        acao: 'dataTipoPag',
        id: id,
    };

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: 'controle.php',
        data: dados,
        beforeSend: function () {

        }, success: function (retorno) {

            var status = retorno.status;

            if (status === 'OK') {
                $('#' + modal).modal('show');
                $('input#tipoPagAlt').val(retorno.dadosArray['tipopag']);
                $('input#inputAltCliente').val(id);
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: retorno,
                    icon: 'error',
                    showConfirmButton: true,
                })
            }

        }
    });

}


// alterar item na dashboad
var sendAltTipoPag = false;

function altTipoPag() {

    // console.log('botao');

    if (!sendAltTipoPag) {

        $('#frmAltTipoPag').submit(function (event) {
            event.preventDefault();

            let form = this;

            let dadosForm = $(this).serializeArray();

            dadosForm.push(
                { name: 'acao', value: 'altTipoPag' },
            )

            // var dados = {
            //     acao: 'addCliente',
            // }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'controle.php',
                data: dadosForm,
                beforeSend: function () {

                },
                success: function (retorno) {

                    if (retorno === 'OK') {
                        $('#modalAltCliente').modal('hide');
                        setTimeout(function () {
                            msgGeral('Alteração efetuada com sucesso!', 'success');
                            listarPage('listarTipoPag');
                            form.reset();
                        }, 500);
                    } else {
                        listarPage('listarTipoPag');
                        msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
                    }

                }

            });

        });

        sendAltTipoPag = true;

        return;

    } else {

        return;

    }

}


// msg para deletar item na dashboard
function msgDelete(id, acao, page) {

    Swal.fire({
        title: 'Você tem certeza?',
        text: "Essa ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não, cancelar!',
        confirmButtonText: 'Sim, apagar registro!'
    }).then((result) => {
        if (result.isConfirmed) {

            var dados = {
                acao: acao,
                id: id,
            };

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'controle.php',
                data: dados,
                beforeSend: function () {

                }, success: function (retorno) {

                    if (retorno === 'OK') {
                        Swal.fire({
                            title: 'Apagado!',
                            text: 'O registro foi deletado com sucesso.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        listarPage(page);
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: retorno,
                            icon: 'error',
                            showConfirmButton: true,
                            timer: 1500
                        })
                        listarPage(page);
                    }

                }
            });
        }
    })

}





// ativar item na dashboard
function ativGeral(id, acao, page) {

    Swal.fire({
        title: 'Você tem certeza que deseja continuar?',
        text: "Essa ação irá alterar o status desse registro.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Não, cancelar!',
        confirmButtonText: 'Sim, alterar registro!'
    }).then((result) => {
        if (result.isConfirmed) {

            var dados = {
                acao: acao,
                id: id,
            };

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: 'controle.php',
                data: dados,
                beforeSend: function () {

                }, success: function (retorno) {

                    if (retorno === 'OK') {
                        Swal.fire({
                            title: 'Alterado!',
                            text: 'O status do registro foi alterado com sucesso.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        listarPage(page);
                    } else {
                        Swal.fire({
                            title: 'Erro!',
                            text: retorno + ' Tente novamente mais tarde.',
                            icon: 'error',
                            showConfirmButton: true,
                        })
                    }

                }
            });
        }
    })

}

// MOVIMENTO TOGGLE GIRATÓRIO

var i = 0;

function expand() {
    if (i == 0) {
        document.getElementById("menu").style.transform = "scale(4)";
        document.getElementById("plus").style.transform = "rotate(137deg)";
        i = 1;
    } else {
        document.getElementById("menu").style.transform = "scale(0)";
        document.getElementById("plus").style.transform = "rotate(0deg)";
        i = 0;
    }
}


// NAVBAR ANIMATION COM INDICATOR DROPLET

const navBar = document.querySelector(".navbar");
const allLi = document.querySelectorAll("li");


allLi.forEach((li, index) => {
    li.addEventListener("click", e => {
        e.preventDefault(); //prevenir de enviar
        var navActive = navBar.querySelector(".active");
        navActive.classList.remove("active");
        // navBar.querySelector(".icon").style.display = ("block");
        // navBar.querySelector(".icon") 
        li.classList.add("active")


        const indicator = document.querySelector(".indicator");
        indicator.style.transform = `translateX(calc(${index * 90}px))`;
        console.log(index)
        console.log(li);
    });
});


// RELÓGIO COM DATA

function clock() {
    var monthNames = ["Jan.", "Fev.", "Mar.", "Abril", "Maio", "Jun.", "Jul.", "Agos.", "Set.", "Out.", "Nov.", "Dez."];
    var dayNames = ["Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado", "Domingo"];

    var Icon = ["<i class='bx bxs-balloon'></i>"];

    var today = new Date();

    document.getElementById('Date').innerHTML = (dayNames[today.getDay()] + " " + Icon + " " + today.getDate() + ' ' + monthNames[today.getMonth()] + ' ' + today.getFullYear());

    var h = today.getHours();
    var m = today.getMinutes();
    // var s = today.getSeconds();
    var day = h < 11 ? 'AM' : 'PM';

    document.getElementById('hours').innerHTML = h;
    document.getElementById('min').innerHTML = m;
    // document.getElementById('sec').innerHTML = s;

    h = h < 10 ? '0' + h : h;
    m = m < 10 ? '0' + m : m;
    // s = s<10? '0'+s: s;
}
var inter = setInterval(clock, 400);

// RELÓGIO COM DATA DE LADO



function clocka() {
    var monthNames = ["Jan.", "Fev.", "Mar.", "Abril", "Maio", "Jun.", "Jul.", "Agos.", "Set.", "Out.", "Nov.", "Dez."];
    var dayNames = ["Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado", "Domingo"];

    var Icon = ["<i class='bx bxs-balloon'></i>"];

    var today = new Date();

    document.getElementById('Datea').innerHTML = (dayNames[today.getDay()] + " " + Icon + " " + today.getDate() + ' ' + monthNames[today.getMonth()] + ' ' + today.getFullYear());

    var h = today.getHours();
    var m = today.getMinutes();
    // var s = today.getSeconds();
    var day = h < 11 ? 'AM' : 'PM';

    document.getElementById('hoursa').innerHTML = h;
    document.getElementById('mina').innerHTML = m;
    // document.getElementById('sec').innerHTML = s;

    h = h < 10 ? '0' + h : h;
    m = m < 10 ? '0' + m : m;
    // s = s<10? '0'+s: s;
}

var inter = setInterval(clocka, 400);


// CALENDÁRIO

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br'
    });
    calendar.render();
});




// function ativarGeral(id, ativo, acao) {
//     $('#' + input).val(id);


//     $('#frmAddCliente').submit(function (event) {
//         event.preventDefault();

//         dadosForm.push(
//             {name: 'acao', value: acao},
//             {name: 'id', value: id},
//             {name: 'valor', value: ativo},
//         )

//         // var dados = {
//         //     acao: 'addCliente',
//         // }

//         $.ajax({
//             type: 'POST',
//             dataType: 'json',
//             url: 'controle.php',
//             data: dadosForm,
//             beforeSend: function () {

//             },
//             success: function (retorno) {

//                 if (retorno === 'OK') {
//                     $('#modalAddCliente').modal('hide');
//                     msgGeral('Cadastro efetuado com sucesso!', 'success');
//                     listarPage('listarCliente');
//                     form.reset();
//                 } else {
//                     listarPage('listarCliente');
//                     msgGeral('ERRO: '+ retorno + ' Tente novamente mais tarde.', 'error');
//                 }
//             }

//         });

//     });

// }


// ativar antigo
// function ativarGeral(id, ativo, idbtn, acao, modal, page) {

//     // console.log(id);
//     // console.log(ativo);
//     // console.log(idbtn);

//     if (ativo == 'ativar') {
//         var btn = idbtn;
//     } else {
//         var btn = idbtn;
//     }
//     $('#' + btn).click(function () {

//         var dados = {
//             acao: acao,
//             id: id,
//             valor: ativo,
//         };

//         $.ajax({
//             type: "POST",
//             dataType: 'json',
//             url: 'controle.php',
//             data: dados,
//             beforeSend: function () {

//             }, success: function (retorno) {

//                 // console.log(id);
//                 // console.log(ativo);
//                 // console.log(idbtn);
//                 // console.log(retorno);

//                 if (retorno === 'OK') {
//                     $('#modal' + modal).modal('hide');
//                     if (ativo == 'ativar') {
//                         msgGeral('O registro foi ativado com sucesso!', 'success');
//                     } else {
//                         msgGeral('O registro foi desativado com sucesso!', 'success');
//                     }
//                     listarPage(page);
//                 } else {
//                     listarPage(page);
//                     msgGeral('ERRO: ' + retorno + ' Tente novamente mais tarde.', 'error');
//                 }

//             }
//         });

//     });
// }