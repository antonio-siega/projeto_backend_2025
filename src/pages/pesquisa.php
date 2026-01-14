<?php
include_once('conecta.php');

// Recupera os valores enviados pelo formulário
$relatorio_escolhido = $_POST['relatorios_id'];
$organizacao_id = $_POST['organizacao_id'] ?? null;
$evento_id = $_POST['evento_id'] ?? null;
$data_inicio = $_POST['data_inicio'] ?? null; 
$data_final = $_POST['data_final'] ?? null;  


$condicoes_where = [];

// Adiciona condições ao array de filtros conforme os valores recebidos
if (!empty($organizacao_id)) {  
    $condicoes_where[] = "e.organizacao_id = " . intval($organizacao_id);
}

if (!empty($evento_id)) {
    $condicoes_where[] = "e.id = " . intval($evento_id);
}

if (!empty($data_inicio)) {
    $condicoes_where[] = "p.data_criacao >= '" . mysqli_real_escape_string($bancodedados, $data_inicio) .  " 00:00:00'";
}
if (!empty($data_final)) {
    $condicoes_where[] = "p.data_criacao <= '" . mysqli_real_escape_string($bancodedados, $data_final) . " 23:59:59'";
}

$filtro_sql = '';

// Constrói a cláusula WHERE com base nas condições acumuladas
if (!empty($condicoes_where)) {
    $filtro_sql = " and " . implode(" and ", $condicoes_where);
}

$filtro_sql2 = !empty($condicoes_where) ? " where " . implode(" and ", $condicoes_where) : "";

$campos_tabela = [];
$sql = '';

// Define a consulta SQL com base no relatório escolhido
switch ($relatorio_escolhido) {
    case 'financeiro':
        $sql = "select e.nome as Evento,
            sum(p.total_liquido) as Receita_Liquida_Total
            from evento e
            join pedido p on e.id = (SELECT setor.evento_id from setor where setor.id = p.setor_id)
            where p.status = 'aprovado'
            " . $filtro_sql . "
            group by e.nome;";
        // Define os campos da tabela para cada relatório
        $campos_tabela = ['Evento', 'Receita Líquida'];
        break;

    case 'vendas_detalhe' :
        $sql = "select c.nome as nome_cliente, p.data_criacao as data_venda,
            l.preco as preco_lote, s.nome as nome_setor, pg.status as status_pagamento,
            e.nome as nome_evento
            from pedido p
            inner join cliente c on p.cliente_id = c.id
            inner join setor s on p.setor_id = s.id
            inner join lote l on p.lote_id = l.id
            inner join pagamento pg on p.id = pg.pedido_id
            inner join evento e on s.evento_id = e.id
            " . $filtro_sql2 . "
            group by p.id, c.nome, p.data_criacao, l.preco, s.nome, pg.status
            order by p.data_criacao desc ;";
        $campos_tabela = ['Nome Cliente', 'Data do pedido', 'Preço','Lote', 'Status', 'Evento'];
        break;

    case 'ocupacao':
        $sql = "select e.nome as Evento, s.nome as Setor,
            count(i.id) as Total_Ingressos_Vendidos
            from evento e
            join setor s ON e.id = s.evento_id
            join pedido p ON s.id = p.setor_id
            join ingresso i ON p.id = i.pedido_id
            where i.status IN ('emitido', 'transferido')
            " . $filtro_sql . "
            group by e.nome, s.nome;";
        $campos_tabela = ['Evento', 'Setor', 'Total de Ingressos'];
        break;
       
    case 'checkin':
    
        $sql = "select e.nome as Evento,
            count(c.id) as Publico_Presente
            from evento e
            join setor s ON e.id = s.evento_id
            join pedido p ON s.id = p.setor_id
            join ingresso i ON p.id = i.pedido_id
            join checkin c ON i.id = c.ingresso_id
             " . $filtro_sql2 . "
            group by e.nome;";
        $campos_tabela = ['Evento', 'Publico Presente'];
        break;
    case 'comissao':
        $sql = "select c.nome as Comissario,
            sum(cp.valor_comissao) as Total_Comissao_Gerada
            from comissario c
            join comissao_pedido cp ON c.id = cp.comissario_id
            join pedido p ON cp.pedido_id = p.id
            join setor s ON p.setor_id = s.id
            join evento e ON s.evento_id = e.id
            where 1=1 
            " . $filtro_sql . "
            group by c.nome
            order by Total_Comissao_Gerada desc;";
         $campos_tabela = ['Nome comissário ', 'Total comissão'];
        break;
    
    // caso nenhum relatório seja escolhido ou o valor será inválido
    default:
       break;
    }

$resultados_relatorio = []; 
// Executa a consulta SQL se houver uma definida
    if (!empty($sql)) {
        
        $resultado = mysqli_query($bancodedados, $sql);
        if ($resultado) {
            while ($linha = mysqli_fetch_assoc($resultado)) {
                $resultados_relatorio[] = $linha;
            }
            mysqli_free_result($resultado);
        }
    }
// Inclui o arquivo de relatórios para exibir os resultados
include('relatorios.php');
?>