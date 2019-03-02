<?php
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');
    use Core\Usuario;
    use Core\Matricula;
    use Core\Turma;
    use Classes\ValidarCampos;
    session_start();
    try{    
        Usuario::verificarPermissoes(array('Professor'));  // apenas professores tem acesso a essa pagina   

        $nomesCampos = array('ID');// Nomes dos campos que receberei da URL    
        $validar = new ValidarCampos($nomesCampos, $_GET);
        $validar->verificarTipoInt(array('ID','CodDis'),$_GET); // Verificar se é um numero

        $usuario = new Usuario();
        $dadosUsuario = $usuario->getDadosUser();  

        $matricula = new Matricula();
        $matricula->setCodTurma($_GET['ID']);        
        $dadosMatriculas = $matricula->getDadosAlunos();      
        
        $turma = new Turma();
        $turma->setCodTurma($_GET['ID']);
        $dadosTurma = $turma->getTipoPeriodoTurma();
        
        
?> 
<!DOCTYPE html>
<html lang=pt-br>
<head>
    
    <title>Nossa nota</title>
    <meta charset=UTF-8> <!-- ISO-8859-1 -->
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <meta name=description content="sistema de notas para professores">
    <meta name=keywords content="notas"> <!-- Opcional -->
    <meta name=author content='Daniel52x e Viniciuswz'>
    
    <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
    
    <!-- CSS PADRÃO -->
    <link href="css/default.css" rel=stylesheet>
    
    <!-- Telas Responsivas -->
    <link rel=stylesheet media="screen and (max-width:480px)" href="css/style480.css">
    <link rel=stylesheet media="screen and (min-width:481px) and (max-width:768px)" href="css/style768.css">
    <link rel=stylesheet media="screen and (min-width:769px) and (max-width:1024px)" href="css/style1024.css">
    <link rel=stylesheet media="screen and (min-width:1025px)" href="css/style1025.css">
    <!-- JS-->
    <script src="lib/_jquery/jquery.js"></script>
    <script src="js/js.js"></script>
    
</head>
<body>
    <div id="container">
        <header>
            <p>Olá, <strong><?php echo $dadosUsuario[0]['nome_usuario']?></strong> !</p>
            <a href="logout.php">logout</a>
        </header>
        <section class="alunos">
            <h1><?php echo $dadosMatriculas[0]['descricao_turma']?>, Sala <?php echo $dadosMatriculas[0]['sala_turma']?></h1>
            <p>Clique em um nome para lançar a nota</p>
            <table border="0" cellpading="0" cellspacing="0">
                <thead>
                    
                    <tr>
                        <th>N°</th>
                        <th>Nome</th>                        
                        <?php
                            $contador2 = 1;
                            while($contador2 <= $dadosTurma[0]['num_perido']){                                        
                                    echo '<th class="nota-table">'.$contador2.$dadosTurma[0]['sigla_perido'].'</th>';                                       
                                $contador2++;
                            }
                        ?>     
                        <th>Status</th>                   
                    </tr>
                    
                </thead>
                <tbody>                    
                        <?php
                            $contador = 0;
                            while($contador < count($dadosMatriculas)){
                                echo "<tr data-id={$dadosMatriculas[$contador]['cod_matricula']}>";
                                echo "<td>{$dadosMatriculas[$contador]['numeroChamada']}</td>";
                                echo "<td><p>{$dadosMatriculas[$contador]['nome_aluno']}</p></td>";                                                                 
                                    $contador2 = 0;
                                    while($contador2 < count($dadosMatriculas[$contador]['notas'])){
                                        if($dadosMatriculas[$contador]['notas'][$contador2][0]['media'] == FALSE){
                                            echo '<td>-</td>';
                                        }else if($dadosMatriculas[$contador]['notas'][$contador2][0]['media'] < 6){
                                            $nota = number_format($dadosMatriculas[$contador]['notas'][$contador2][0]['media'], 2, '.', '');                                           
                                            echo "<td><p style='color:red'>{$nota}</p></td>";
                                        }else{
                                            $nota = number_format($dadosMatriculas[$contador]['notas'][$contador2][0]['media'], 2, '.', '');  
                                            echo "<td><p>{$nota}</p></td>";
                                        }
                                        
                                        $contador2++;
                                    }            
                                echo "<td><p>{$dadosMatriculas[$contador]['status_matricula']}</p></td>";                          
                                echo "</tr>";
                                $contador++;
                            }
                        ?>   
                </tbody>
            </table>
        </section>
    </div>
    
    <div class="modal" >
        <div class="fundo-modal">
            <span style="color: azure; font-size: 40px; font-weight: bold; position: absolute; top: 10px;right: 30px; cursor: pointer">×</span>
        </div>
        <div>
            <div class="tabs">
                <?php
                    $contador = 1;
                    while($contador <= $dadosTurma[0]['num_perido']){
                        if($contador == 1){
                            echo '<a href="#" data-tipo="'.$contador.'" class="tab-ativo">'.$contador.$dadosTurma[0]['sigla_perido'].'</a>';
                        }else{
                            echo '<a href="#" data-tipo="'.$contador.'">'.$contador.$dadosTurma[0]['sigla_perido'].'</a>';
                        }
                        $contador++;
                    }
                ?>           
            </div>
        <h2 style="text-align:center"></h2>
            <form action="" id="lancar-nota">
                <div class="nota-input">
                    <label for="nota1">Nota 1</label>
                    <input type="number" id="nota1" name="nota1" min="0" max="10" step="0.1" maxlenght="3" required>
                </div>
                <div class="nota-input">
                    <label for="nota2">Nota 2</label>
                    <input type="number" id="nota2" name="nota2" min="0" max="10" step="0.1" maxlenght="3" required>
                </div>
                <div class="nota-input">
                    <label for="nota3">Nota 3</label>
                    <input type="number" id="nota3" name="nota3" min="0" max="10" step="0.1" maxlenght="3" required>
                </div>
                <div class="nota-input">
                    <label for="nota4">Nota 4</label>
                    <input type="number" id="nota4" name="nota4" min="0" max="10" step="0.1" maxlenght="3" required>
                </div>
                <input type="hidden" id="tipo" name="tipo" value="1">
                <input type="hidden" id="id" name="id" value="0">
                <input type="submit" value="enviar">
            </form>
            <p><strong>Média <?php echo $dadosTurma[0]['descricao_periodo']?></strong><span id="media">-</span></p>
        </div>
    </div>
    
</body>
</html>
<?php    
    }catch (Exception $exc){    
        echo $exc->getMessage();
    }
