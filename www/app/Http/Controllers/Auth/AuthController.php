<?php 
//CONTROLLER NÃO É FUNCIONAL - APENAS PARA GERAR A DOCUMENTAÇÃO

    /**
     * Ducumentação para Rota Oahty
     *
     * <p>Este Endpoint Realiza o <strong>Upload e Processamento do Arquivo</strong>. É Necessário para um valor chamado <strong>arquivo</strong> no body da requisição, nos formatos CSV ou XLSX</p>.
     *
     * @authenticated
     * @header Authorization Bearer {ACCESS_TOKEN}
     * @response 201 { 
     *     "message": "O Arquivo nome_arquivo Está Sendo Processado!", 
     * }
     * @response 409 { 
     *      "message": "O Arquivo Enviado Já Consta em Nosso Sistema"
     * }
     * @response 401 {
     *      "message": "Unauthenticated."
     * }
     */