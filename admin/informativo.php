<?php

wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.min.css', __FILE__));

wp_enqueue_script('jquery');	
wp_enqueue_script('wpcva_bootstrapJS', plugins_url('../js/bootstrap.min.js', __FILE__));

?>
<div id="wp-cvpf">
<div class="container-fluid">
    <h2>Informações do plugin</h2>					
    <p>O <strong>WP-Curriculo Vitae</strong> e um plugin que permite que usuários a cadastrem seu currículo no site para divulgação online ou para uso do site.</p>
    <p>O plugin trabalha com duas versoes:</p>

    <p><b>Versao gratuita:</b></p>
    
    <ul style=" list-style: decimal; margin-left: 25px;">
        <li>Cadastrar o currículo;</li>
        <li>Listagem de currículos com sistema de busca;</li>
        <li>Listagem de de área de serviços;</li>
        <li>Editar através de área restrita;</li>
        <li>Shortcode para formulário de cadastro <b>[formCadastro]</b>;</li>
        <li>Shortcode para lista de currículos <b>[listCurriculos]</b>;</li>
    </ul>

    <p><b>Versao paga:</b></p>
     
    <ul style=" list-style: decimal; margin-left: 25px;">
        <li>Cadastrar o currículo;</li>
        <li>Editar através de área restrita;</li>
        <li>Widget de login na área restrita;</li>
        <li>Listagem de currículos com sistema de busca;</li>
        <li>Área restrita pode salvar os currículo em PDF;</li>
        <li>Relatório de todos currículo em PDF;</li>
        <li>Lista de currículo área admin com filtro de pesquisa;</li>
        <li>Widget de busca de currículos;</li>
        <li>Widget de login para cadastrado, com permissão de edição e exclusão do mesmo;</li>
        <li>Sistema de aprovação de cadastrados;</li>
        <li>Exportação de cadastrados;</li>
        <li>Shortcode para formulário de cadastro <b>[formCadastro_cvp]</b>;</li>
        <li>Shortcode para lista de currículos <b>[listCurriculos_cvp]</b>;</li>
    </ul> 
    
    <p><a href="http://williamluis.com.br/loja/" target="_blank">Link para para plugin pago</a></p>
    
    <p><strong>Como utilizar</strong></p>
    <p>Para exibir o formulário de cadastro, crie uma pagina e coloque o shortcode <strong>[formCadastro]</strong>.</p>
    <p>Para exibir a listagem dos cadastrados, crie uma página e coloque o shortcode <strong>[listCurriculos]</strong>.</p>
    <p>Use o widget <strong>WP-Curriculo Vitae busca</strong> para exibir a busca rápida de currículo em todas as páginas.</p>
    <p>Use o widget <strong>WP-Curriculo Vitae login</strong> para exibir o formulário de login em todas as páginas.</p>
    <p><em>Obs.</em> Os widget são apenas para a versão paga.</p>
    <p>Qualquer dúvida envie uma mensagem para o email <a href="mailto:wiliamluisilva@gmail.com">wiliamluisilva@gmail.com</a> ou acesse o blog e deixe uma mensagem no contato <a href="http://wiliamluis.wordpress.com/contato/">clicando aqui</a>.</p>
</div>
</div>