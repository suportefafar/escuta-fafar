"use strict";

let isBtnEditar = true;

const container_btn_editar_salvar =
  document.querySelector("#btn-editar-salvar");

container_btn_editar_salvar?.addEventListener(
  "click",
  manipuladorBtnEditarSalvar
);

function manipuladorBtnEditarSalvar(e) {
  if (isBtnEditar) {
    isBtnEditar = false;
    manipuladorBtnEditar();
  } else {
    isBtnEditar = true;
    manipuladorBtnSalvar();
  }
}

/*

*/

function manipuladorBtnEditar() {
  /*
      Mudando ícone do botão
    */
  container_btn_editar_salvar?.classList.remove("container-btn-editar");
  container_btn_editar_salvar?.classList.add("container-btn-salvar");

  /*
      Mudando texto do botão
    */

  container_btn_editar_salvar.querySelector(".container-btn-texto").innerHTML =
    "Salvar";

  /*
      Mudando estilo do botão
    */
  container_btn_editar_salvar
    ?.querySelector(".dashicons")
    ?.classList.remove("dashicons-edit");
  container_btn_editar_salvar
    ?.querySelector(".dashicons")
    ?.classList.add("dashicons-saved");

  /*
    Tornando as células editáveis
  */
  const linhas_info_perfil = document.querySelectorAll(".linha-info-perfil");
  linhas_info_perfil.forEach((linha) => {
    const td_info_valor = linha.querySelector(".info-valor");

    td_info_valor.setAttribute("contenteditable", "true");

    td_info_valor.classList.add("td-info-valor-editaval");
  });
}

function manipuladorBtnSalvar() {
  /*
      Mudando ícone do botão
    */
  container_btn_editar_salvar?.classList.add("container-btn-editar");
  container_btn_editar_salvar?.classList.remove("container-btn-salvar");

  /*
      Mudando texto do botão
    */

  container_btn_editar_salvar.querySelector(".container-btn-texto").innerHTML =
    "Editar";

  /*
      Mudando estilo do botão
    */
  container_btn_editar_salvar
    ?.querySelector(".dashicons")
    ?.classList.add("dashicons-edit");
  container_btn_editar_salvar
    ?.querySelector(".dashicons")
    ?.classList.remove("dashicons-saved");

  /*
    Tornando as células editáveis
  */
  const linhas_info_perfil = document.querySelectorAll(".linha-info-perfil");
  linhas_info_perfil.forEach((linha) => {
    const tr_info_valor = linha.querySelector(".info-valor");

    tr_info_valor.setAttribute("contenteditable", "false");

    tr_info_valor.classList.remove("td-info-valor-editaval");
  });
}
