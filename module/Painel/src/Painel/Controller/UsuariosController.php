<?php
namespace Painel\Controller;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Model\ModelAnaliseExperienciaProfissional;
use Application\Model\ModelAnaliseCursos;
use Application\Model\ModelAnaliseExperienciaInternacional;
use Application\Model\ModelDadosEscolares;
use Application\Model\ModelPosGraduacao;
use Application\Model\ModelIdiomas;
use Application\Model\ModelAnaliseDeCurriculo;
use Application\Model\ModelResultadosFinais;
use Application\Model\ModelAnaliseEntrevista;
use Application\Model\ModelInteligenciasMultiplas;
use Application\Model\ModelCompetencias;
use Application\Model\ModelResultadosFinaisCompleto;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelPontosFortes;
use Application\Model\ModelPerfilComportamental;

class UsuariosController extends \Application\Classes\GlobalController
{
	private function init()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		//SELECIONA USUARIO
		$filter = array();
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['users'] = $modellogin->get2($filter, true, 'page', 15);
		
		//echo '<pre>'; print_r($this->view['users']->toArray()); exit;
		
		
		//title
		$this->head->setTitle('Usuários');
		
	}
	
	public function indexAction()
	{
	    
		$this->init();
		$this->view['filter'] = $this->params()->fromQuery();
		
		if ( $_POST['page_10'] ){
		    $_GET['page_10'] = $_POST['page_10'];
		}
		
		//echo '<pre>'; print_r($this->view['filter']); exit;

		//filtrar usuarios do site
		$where = " (login.nivel = 0) ";

		//filtrar testes finalizados
		if( empty($this->view['filter']['testes_nao_finalizados']) && $this->view['filter']['testes_finalizados'] == "on" )
		{
		    $where .= ' AND perfil_comportamental = "Completo"';
		    $where .= ' AND aptidoes_profissionais = "Completo"';
		    $where .= ' AND inteligencias_multiplas = "Completo"';
		    $where .= ' AND pontos_fortes = "Completo"';
		    $where .= ' AND competencias = "Completo"';
		}
		
		//filtrar testes nao finalizados
		if( $this->view['filter']['testes_nao_finalizados'] == "on" )
		{
		    $where .= ' AND ( ';
		    $where .= ' perfil_comportamental IS NULL';
		    $where .= ' OR aptidoes_profissionais IS NULL';
		    $where .= ' OR inteligencias_multiplas  IS NULL';
		    $where .= ' OR pontos_fortes IS NULL';
		    $where .= ' OR competencias IS NULL';
		    $where .= ' ) ';
		}
		
		//filtrar nome
		if( !empty($this->view['filter']['nome']) )
		{
		    $where .= ' AND login.nome = "' . $this->view['filter']['nome'] . '"';
		}

		//filtrar data de nascimento
		if( !empty($this->view['filter']['data_nascimento']) )
		{
		    $date = implode('-', array_reverse(explode('/', $this->view['filter']['data_nascimento'])));
		    $where .= ' AND SUBSTR(analiseDeCurriculo.data_de_nascimento,1,10) >= "' . $date . '"';
		}
		
		//filtrar data
		if( !empty($this->view['filter']['data_inicial']) )
		{
		    $date = implode('-', array_reverse(explode('/', $this->view['filter']['data_inicial'])));
		    $where .= ' AND SUBSTR(login.criado,1,10) >= "' . $date . '"';
		}
		if( !empty($this->view['filter']['data_final']) )
		{
		    $date = implode('-', array_reverse(explode('/', $this->view['filter']['data_final'])));
		    $where .= ' AND SUBSTR(login.criado,1,10) <= "' . $date . '"';
		}
		
		//filtrar competencias
		if( $this->view['filter']['cp1'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp1 IS NOT NULL';
		}
		if( $this->view['filter']['cp2'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp2 IS NOT NULL';
		}
		if( $this->view['filter']['cp3'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp3 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp4'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp4 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp5'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp5 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp6'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp6 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp7'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp7 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp8'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp8 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp9'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp9 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp10'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp10 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp11'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp11 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp12'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp12 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp13'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp13 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp14'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp14 IS NOT NULL';
		}
		
		if( $this->view['filter']['cp15'] == "on" )
		{
		    $where .= ' AND resultadosFinais.cp15 IS NOT NULL';
		}
		
		//filtrar carreiras
		if( $this->view['filter']['academica'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "academica"';
		}
		
		if( $this->view['filter']['gerencial'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "gerencial"';
		}
		
		if( $this->view['filter']['empreendedora'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "empreendedora"';
		}
		
		if( $this->view['filter']['especialista'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "especialista"';
		}
		
		if( $this->view['filter']['publica'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "publica"';
		}
		
		if( $this->view['filter']['politica'] == "on" )
		{
		    $where .= ' AND resultadosFinais.maior_carreira = "politica"';
		}
		
		//GRADUAÇÃO
		
		if( $this->view['filter']['graduando'] == "on" )
		{
		    $where .= ' AND dadosescolares.status_curso = "Em andamento"';
		}
		
		if( $this->view['filter']['formado'] == "on" )
		{
		    $where .= ' AND dadosescolares.status_curso = "Concluído"';
		}
		
		
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['users'] = $model->get3(["expr"=>$where], true, 'page_10', 25);
  		
		if ( $_POST['tipo'] == 'download_excel_1' || $_GET['tipo'] == 'download_excel_1' ) {
		    $this->downloadExcel_1( $this->view['users'], $model->paginacao );
		}

		if ( $_POST['tipo'] == 'download_excel_2' || $_GET['tipo'] == 'download_excel_2' ) {
		    $this->downloadExcel_2( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'download_excel_3' || $_GET['tipo'] == 'download_excel_3' ) {
		    $this->downloadExcel_3( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'download_excel_4' || $_GET['tipo'] == 'download_excel_4' ) {
		    $this->downloadExcel_4( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'download_excel_5' || $_GET['tipo'] == 'download_excel_5' ) {
		    $this->downloadExcel_5( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'download_excel_6' || $_GET['tipo'] == 'download_excel_6' ) {
		    $this->downloadExcel_6( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'indicePerdidice' || $_GET['tipo'] == 'indicePerdidice' ) {
		    $this->indicePerdidice( $this->view['users'], $model->paginacao );
		}
		
		if ( $_POST['tipo'] == 'resultadosFinais' || $_GET['tipo'] == 'resultadosFinais' ) {
		    $this->resultadosFinais( $this->view['users'], $model->paginacao );
		}
		
		
		//view
		$this->head->addCalendar();
		$this->head->addMask();
		return new ViewModel($this->view);
		
	}
	
	public function detalheAction()
	{
		$get = $this->params()->fromQuery();
		
		$filter = array();
		$filter['expr'] = 'login.id_login = "' . $get['id']. '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['usuario'] = $modellogin->get2($filter)->current();
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $get['id']. '"';
		$modelanalise = new ModelAnaliseEntrevista($this->tb, $this->adapter);
		$this->view['analise'] = $modelanalise->get($filter)->current();

		$filter = array();
		$filter['expr'] = 'login.id_login = "' . $get['id']. '"';
		$filter['expr'] .= ' AND perfil_comportamental = "Completo"';
		$filter['expr'] .= ' AND aptidoes_profissionais = "Completo"';
		$filter['expr'] .= ' AND inteligencias_multiplas = "Completo"';
		$filter['expr'] .= ' AND pontos_fortes = "Completo"';
		$filter['expr'] .= ' AND competencias = "Completo"';
		$modelloginrf = new ModelLogin($this->tb, $this->adapter);
		$this->view['usuariorelfinal'] = $modelloginrf->get2($filter)->current();
		
		
		//echo '<pre>'; print_r($this->view['usuario']); exit;
		
		return new ViewModel($this->view);
	}
	
	public function curriculoAction()
	{
		$get = $this->params()->fromQuery();
		//echo $get['id']; exit;
		
		//seleciona os dados do usuario
		$filter = array();
		$filter['expr'] = 'id_login = "' . $get['id'] . '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['usuario'] = $model->get($filter)->toArray();
		
		//seleciona os dados do curriculo
		$filter = array();
		$filter['expr'] = 'login.id_login = "' . $get['id']. '"';
		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
		$this->view['data'] = $model->get($filter)->current();
		$result_analise = $model->get($filter)->current();
		$id_analise = $result_analise['id_analise_de_curriculo'];
		
		//echo '<pre>'; print_r($this->view['usuario']); exit;
		
		//selecionar info complementares
		$filter = array();
		$filter['expr'] = 'login.id_login = "' . $get['id'] . '"';
		$filter['expr'] .= ' AND id_analise_de_curriculo = "'. $id_analise .'"';
		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
		$this->view['info_complementares'] = $model->get($filter)->current();
		
		//echo '<pre>'; print_r($this->view['info_complementares']); exit;
		
		//selecionar experiencia profissional
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['order'] = 'data_de_inicio_empresarial DESC';
		$filter['expr'] .= ' AND status != "Excluido"';
		$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
		$this->view['profissional'] = $model->get($filter)->toArray();
		
		//selecionar cursos
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Curso"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_cursos'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Minicurso"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_minicurso'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Workshop"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_workshop'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Outro"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_outro'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Evento"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_evento'] = $modelCursos->get($filter)->toArray();
		
		//selecionar experiencia internacional
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
		$this->view['internacional'] = $modelCursos->get($filter)->toArray();
		
		//selecionar dados escolares
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelDadosEscolares($this->tb, $this->adapter);
		$this->view['dados_escolares'] = $modelCursos->get($filter)->toArray();
		
		//selecionar Pos
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelPosGraduacao($this->tb, $this->adapter);
		$this->view['dados_pos'] = $modelCursos->get($filter)->toArray();
		
		//selecionar Pos
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $id_analise .'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelIdiomas($this->tb, $this->adapter);
		$this->view['dados_idiomas'] = $modelCursos->get($filter)->toArray();
		
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function saveAction()
	{
		$params = $this->params()->fromPost();
		
		//echo '<pre>'; print_r($params['id_login']); exit;
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $params['id_login']. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['user'] = $model->get($filter)->current();
		
			
		//upload
		$file = $_FILES['file_pdi'];
		if( !empty($file['name']) )
		{
			$path = "/assets/application/uploads/";
			$upload = new \Naicheframework\Upload\Upload($path);
			$upload->setExtensions(["jpg","png","gif","pdf","doc"])->file($file, "pdi");
			if( !empty($upload->getError()) ) throw new \Exception("Erro no upload da imagem!");
			$params['file_pdi'] = $upload->getFilenameCurrent();
		}
		
			
		$params['id_login'] = $params['id_login'];
		$model = new ModelLogin($this->tb, $this->adapter);
		
		//salva no banco
		$model->save($params, $params['id_login']);
		
		//redirect
		return $this->redirect()->toUrl('/painel/usuarios/detalhe?id='.$params['id_login']);
	}
	
	protected function downloadExcel_1( $qry = [], $paginacao = [] )
	{
	    
	    $modelIdiomas = new ModelIdiomas($this->tb, $this->adapter);
	    $modelAnaliseExperienciaProfissional = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
	    $modelAnaliseExperienciaInternacional = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
	    $modelDadosEscolares = new ModelDadosEscolares($this->tb, $this->adapter);
	    $modelPosGraduacao = new ModelPosGraduacao($this->tb, $this->adapter);
	    $modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
	    
	    $result = [];
        $result['paginacao'] = $paginacao;	    
        $result['result'] = [];
        
	    foreach ( $qry as $key => $row ){
	        
	        $result['result'][$key] = (array)($row);
	        
	        //idiomas
	        $idiomas = $modelIdiomas->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $quais_idiomas = [];
	        foreach ( $idiomas as $row ){
	            $quais_idiomas[] = $row['idiomas'];
	        }
	        
	        $nivel_idiomas = [];
	        foreach ( $idiomas as $row ){
	            $nivel_idiomas[] = $row['nivel'];
	        }
	        
	        $result['result'][$key]['possui_idioma'] = count($idiomas) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['qual_idioma'] = implode(', ', $quais_idiomas);
	        $result['result'][$key]['nivel_idioma'] = implode(', ', $nivel_idiomas);
	        
	        //experiencia analise profissional
	        $analiseExperienciaProfissional = $modelAnaliseExperienciaProfissional->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $tipo_experiencia_profissional = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $tipo_experiencia_profissional[] = $row['tipo_da_experiencia'];
	        }
	        $empresas = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $empresas[] = $row['nome_da_empresa'];
	        }
	        $cargo = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $cargo[] = $row['cargo'];
	        }
	        $regime_de_contrato = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $regime_de_contrato[] = $row['regime_de_contrato'];
	        }
	        $data_de_inicio_empresarial = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $data_de_inicio_empresarial[] = date('d/m/Y', strtotime( $row['data_de_inicio_empresarial']));
	        }
	        $trabalho_atual = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $trabalho_atual[] = $row['trabalho_atual'];
	        }
	        $data_de_termino_empresarial = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $data_de_termino_empresarial[] = $row['data_de_termino_empresarial'];
	        }
	        $resumo_das_atividades = [];
	        foreach ( $analiseExperienciaProfissional as $row ){
	            $resumo_das_atividades[] = $row['resumo_das_atividades'];
	        }
	        
	        $result['result'][$key]['possui_experiencia_profissional'] = count($analiseExperienciaProfissional) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['tipo_experiencia_profissional'] = implode(', ', $tipo_experiencia_profissional);
	        $result['result'][$key]['qual_empresa'] = implode(', ', $empresas);
	        $result['result'][$key]['cargo'] = implode(', ', $cargo);
	        $result['result'][$key]['regime_de_contrato'] = implode(', ', $regime_de_contrato);
	        $result['result'][$key]['data_de_inicio_empresarial'] = implode(', ', $data_de_inicio_empresarial);
	        $result['result'][$key]['trabalho_atual'] = implode(', ', $trabalho_atual);
	        $result['result'][$key]['data_de_termino_empresarial'] = implode(', ', $data_de_termino_empresarial);
	        $result['result'][$key]['resumo_das_atividades'] = implode(', ', $resumo_das_atividades);
	        
	        //Experiencia internacional
	        $analiseExperienciaInternacional = $modelAnaliseExperienciaInternacional->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $tipo_de_experiencia = [];
	        foreach ( $analiseExperienciaInternacional as $row ){
	            $tipo_de_experiencia[] = $row['tipo_de_experiencia'];
	        }

	        $tempo_de_duracao = [];
	        foreach ( $analiseExperienciaInternacional as $row ){
	            $tempo_de_duracao[] = $row['tempo_de_duracao'];
	        }
	        
	        $pais_experiencia = [];
	        foreach ( $analiseExperienciaInternacional as $row ){
	            $pais_experiencia[] = $row['pais_experiencia'];
	        }
	        
	        $result['result'][$key]['possui_experiencia_internacional'] = count($analiseExperienciaInternacional) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['tipo_de_experiencia'] = implode(', ', $tipo_de_experiencia);
	        $result['result'][$key]['tempo_de_duracao'] = implode(', ', $tempo_de_duracao);
	        $result['result'][$key]['pais_experiencia'] = implode(', ', $pais_experiencia);
	        
	        //Dados Escolares
	        $dadosEscoalres = $modelDadosEscolares->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $estado_da_instituicao_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $estado_da_instituicao_andamento[] = $row['estado_da_instituicao_andamento'];
	        }

	        $instituicao_de_ensino_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $instituicao_de_ensino_andamento[] = $row['instituicao_de_ensino_andamento'];
	        }
	        
	        $nome_do_curso_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $nome_do_curso_andamento[] = $row['nome_do_curso_andamento'];
	        }
	        
	        $tipo_do_curso_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $tipo_do_curso_andamento[] = $row['tipo_do_curso_andamento'];
	        }
	        
	        $tipo_do_curso_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $tipo_do_curso_andamento[] = $row['tipo_do_curso_andamento'];
	        }
	        
	        $periodo_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
                $periodo_andamento[] = $row['periodo_andamento'];
	        }
	        
	        $duracao_do_curso_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $duracao_do_curso_andamento[] = $row['duracao_do_curso_andamento'];
	        }
	        
	        $horario_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $horario_andamento[] = $row['horario_andamento'];
	        }
	        
	        $previsao_de_formatura_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $previsao_de_formatura_andamento[] = $row['previsao_de_formatura_andamento'];
	        }
	        
	        $disponibilidade_estagio_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $disponibilidade_estagio_andamento[] = $row['disponibilidade_estagio_andamento'];
	        }
	        
	        $coeficiente_de_rendimento_andamento = [];
	        foreach ( $dadosEscoalres as $row ){
	            $coeficiente_de_rendimento_andamento[] = $row['coeficiente_de_rendimento_andamento'];
	        }
	        
	        $estado_da_instituicao_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $estado_da_instituicao_concluido[] = $row['estado_da_instituicao_concluido'];
	        }
	        
	        $instituicao_de_ensino_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $instituicao_de_ensino_concluido[] = $row['instituicao_de_ensino_concluido'];
	        }
	        
	        $nome_do_curso_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $nome_do_curso_concluido[] = $row['nome_do_curso_concluido'];
	        }
	        
	        $tipo_do_curso_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $tipo_do_curso_concluido[] = $row['tipo_do_curso_concluido'];
	        }
	        
	        $tipo_do_curso_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $tipo_do_curso_concluido[] = $row['tipo_do_curso_concluido'];
	        }
	        
	        $periodo_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $periodo_concluido[] = $row['periodo_concluido'];
	        }
	        
	        $duracao_do_curso_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $duracao_do_curso_concluido[] = $row['duracao_do_curso_concluido'];
	        }
	        
	        $horario_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $horario_concluido[] = $row['horario_concluido'];
	        }
	        
	        $previsao_de_formatura_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $previsao_de_formatura_concluido[] = $row['previsao_de_formatura_concluido'];
	        }
	        
	        $disponibilidade_estagio_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $disponibilidade_estagio_concluido[] = $row['disponibilidade_estagio_concluido'];
	        }
	        
	        $coeficiente_de_rendimento_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $coeficiente_de_rendimento_concluido[] = $row['coeficiente_de_rendimento_concluido'];
	        }
	        $conclusao_de_curso_concluido = [];
	        foreach ( $dadosEscoalres as $row ){
	            $conclusao_de_curso_concluido[] = $row['conclusao_de_curso_concluido'];
	        }
	        
	        $result['result'][$key]['possui_graduacao'] = count($dadosEscoalres) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['estado_da_instituicao_andamento'] = implode(', ', $estado_da_instituicao_andamento);
	        $result['result'][$key]['instituicao_de_ensino_andamento'] = implode(', ', $instituicao_de_ensino_andamento);
	        $result['result'][$key]['nome_do_curso_andamento'] = implode(', ', $nome_do_curso_andamento);
	        $result['result'][$key]['tipo_do_curso_andamento'] = implode(', ', $tipo_do_curso_andamento);
	        $result['result'][$key]['periodo_andamento'] = implode(', ', $periodo_andamento);
	        $result['result'][$key]['duracao_do_curso_andamento'] = implode(', ', $duracao_do_curso_andamento);
	        $result['result'][$key]['horario_andamento'] = implode(', ', $horario_andamento);
	        $result['result'][$key]['previsao_de_formatura_andamento'] = implode(', ', $previsao_de_formatura_andamento);
	        $result['result'][$key]['disponibilidade_estagio_andamento'] = implode(', ', $disponibilidade_estagio_andamento);
	        $result['result'][$key]['coeficiente_de_rendimento_andamento'] = implode(', ', $coeficiente_de_rendimento_andamento);
	        $result['result'][$key]['estado_da_instituicao_concluido'] = implode(', ', $estado_da_instituicao_concluido);
	        $result['result'][$key]['instituicao_de_ensino_concluido'] = implode(', ', $instituicao_de_ensino_concluido);
	        $result['result'][$key]['nome_do_curso_concluido'] = implode(', ', $nome_do_curso_concluido);
	        $result['result'][$key]['tipo_do_curso_concluido'] = implode(', ', $tipo_do_curso_concluido);
	        $result['result'][$key]['periodo_concluido'] = implode(', ', $periodo_concluido);
	        $result['result'][$key]['duracao_do_curso_concluido'] = implode(', ', $duracao_do_curso_concluido);
	        $result['result'][$key]['horario_concluido'] = implode(', ', $horario_concluido);
	        $result['result'][$key]['conclusao_de_curso_concluido'] = implode(', ', $conclusao_de_curso_concluido);
	        $result['result'][$key]['previsao_de_formatura_concluido'] = implode(', ', $previsao_de_formatura_concluido);
	        $result['result'][$key]['disponibilidade_estagio_concluido'] = implode(', ', $disponibilidade_estagio_concluido);
	        $result['result'][$key]['coeficiente_de_rendimento_concluido'] = implode(', ', $coeficiente_de_rendimento_concluido);
	        
	        //Pos graduacao
	        
	        $posGraduacao = $modelPosGraduacao->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $tipo_pos = [];
	        foreach ( $posGraduacao as $row ){
	            $tipo_pos[] = $row['tipo_pos'];
	        }
	        
	        $estado_da_instituicao_pos = [];
	        foreach ( $posGraduacao as $row ){
	            $estado_da_instituicao_pos[] = $row['estado_da_instituicao_pos'];
	        }
	        
	        $instituicao_de_ensino_pos = [];
	        foreach ( $posGraduacao as $row ){
	            $instituicao_de_ensino_pos[] = $row['instituicao_de_ensino_pos'];
	        }
	        
	        $nome_da_instituicao_pos = [];
	        foreach ( $posGraduacao as $row ){
	            $nome_da_instituicao_pos[] = $row['nome_da_instituicao_pos'];
	        }
	        
	        $status_curso_pos = [];
	        foreach ( $posGraduacao as $row ){
	            $status_curso_pos[] = $row['status_curso_pos'];
	        }

	        $result['result'][$key]['possui_posgraduacao'] = count($posGraduacao) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['tipo_pos'] = implode(', ', $tipo_pos);
	        $result['result'][$key]['estado_da_instituicao_pos'] = implode(', ', $estado_da_instituicao_pos);
	        $result['result'][$key]['instituicao_de_ensino_pos'] = implode(', ', $instituicao_de_ensino_pos);
	        $result['result'][$key]['nome_do_curso_pos'] = implode(', ', $nome_da_instituicao_pos);
	        $result['result'][$key]['status_curso_pos'] = implode(', ', $status_curso_pos);
	        
	        //Cursos
	        $cursos = $modelCursos->get( ['expr'=>'id_analise_de_curriculo = "'.$result['result'][$key]['id_analise_de_curriculo'].'"'] )->toArray();
	        
	        $tipo_de_curso = [];
	        foreach ( $cursos as $row ){
	            $tipo_de_curso[] = $row['tipo_de_curso'];
	        }
	        
	        $nome_do_curso = [];
	        foreach ( $cursos as $row ){
	            $nome_do_curso[] = $row['nome_do_curso'];
	        }
	        
	        $nivel_do_curso = [];
	        foreach ( $cursos as $row ){
	            $nivel_do_curso[] = $row['nivel_do_curso'];
	        }
	        
	        $carga_horaria = [];
	        foreach ( $cursos as $row ){
	            $carga_horaria[] = $row['carga_horaria'];
	        }
	        
	        $conclusao_curso = [];
	        foreach ( $cursos as $row ){
	            $conclusao_curso[] = $row['conclusao_curso'];
	        }
	        
	        $result['result'][$key]['possui_cursos'] = count($cursos) == 0 ? 'Não' : 'Sim';
	        $result['result'][$key]['tipo_de_curso'] = implode(', ', $tipo_de_curso);
	        $result['result'][$key]['nome_do_curso'] = implode(', ', $nome_do_curso);
	        $result['result'][$key]['nivel_do_curso'] = implode(', ', $nivel_do_curso);
	        $result['result'][$key]['carga_horaria'] = implode(', ', $carga_horaria);
	        $result['result'][$key]['conclusao_curso'] = implode(', ', $conclusao_curso);
	        
	        
	    }
	    
        $csv = '';	    
	    
        if ( $_GET['page_10'] == 1 ){
    	    $csv .= 'Nome;';
    	    $csv .= 'Data de Nascimento;';
    	    $csv .= 'Email;';
    	    $csv .= 'Sexo;';
    	    $csv .= 'Estado Civil;';
    	    $csv .= 'Filhos;';
    	    $csv .= 'Nacionalidade;';
    	    $csv .= 'CEP;';
    	    $csv .= 'Endereço;';
    	    $csv .= 'Número;';
    	    $csv .= 'Complemento;';
    	    $csv .= 'Bairro;';
    	    $csv .= 'Estado;';
    	    $csv .= 'Cidade;';
    	    $csv .= 'Tel Resicencial;';
    	    $csv .= 'Tel Celular;';
    	    $csv .= 'RG;';
    	    $csv .= 'Orgão emissor;';
    	    $csv .= 'Carteira de Trabalho;';
    	    $csv .= 'Graduação;';
    	    $csv .= 'Estado da instituição de ensino  em andamento;';
    	    $csv .= 'Nome da Instituição de Ensino  em andamento;';
    	    $csv .= 'Nome do curso  em andamento;';
    	    $csv .= 'Tipo do curso  em andamento;';
    	    $csv .= 'Período do curso  em andamento;';
    	    $csv .= 'Duração em andamento;';
    	    $csv .= 'Horário em andamento;';
    	    $csv .= 'Previsão de formatura em andamento;';
    	    $csv .= 'Disponibilidade de Estágio em andamento;';
    	    $csv .= 'Coeficiente de Rendimento em andamento;';
    	    $csv .= 'Estado da instituição de ensino concluido;';
    	    $csv .= 'Nome da Instituição de Ensino concluido;';
    	    $csv .= 'Nome do curso concluido;';
    	    $csv .= 'Tipo do curso concluido;';
    	    $csv .= 'Período do curso concluido;';
    	    $csv .= 'Duração concluido;';
    	    $csv .= 'Horário concluido;';
    	    $csv .= 'Coeficiente de Rendimento concluido;';
    	    $csv .= 'Ano de conclusão;';
    	    $csv .= 'Pós-Graduação;';
    	    $csv .= 'Tipo de pós-graduação;';
    	    $csv .= 'Estado da Instituição de Ensino;';
    	    $csv .= 'Nome da Instituição;';
    	    $csv .= 'Nome do curso;';
    	    $csv .= 'Status do Curso;';
    	    $csv .= 'Atividades extracurriculares;';
    	    $csv .= 'Tipo;';
    	    $csv .= 'Qual;';
    	    $csv .= 'Nivel;';
    	    $csv .= 'Carga horária;';
    	    $csv .= 'Ano de conclusão;';
    	    $csv .= 'Idiomas;';
    	    $csv .= 'Qual;';
    	    $csv .= 'Nível;';
    	    $csv .= 'Você possui Experiência Profissional?;';
    	    $csv .= 'Tipo da Experiência?;';
    	    $csv .= 'Nome da Empresa;';
    	    $csv .= 'Cargo;';
    	    $csv .= 'Regime de Contrato;';
    	    $csv .= 'Data de início;';
    	    $csv .= 'Trabalho atual;';
    	    $csv .= 'Data término;';
    	    $csv .= 'Resumo das atividades;';
    	    $csv .= 'Objetivo Profissional;';
    	    $csv .= 'Experiência Internacional;';
    	    $csv .= 'Tipo de Experiência;';
    	    $csv .= 'Tempo de duração;';
    	    $csv .= 'País;';
    	    $csv .= 'Você possui CNH?;';
    	    $csv .= 'É fumante?;';
    	    $csv .= 'Alguma deficiência?;';
    	    $csv .= 'Qual?;';
    	    $csv .= 'Mora com o responsável legal?;';
    	    $csv .= 'Possui conta bancária?;';
    	    $csv .= 'Possui cartão de crédito próprio? ;';
    	    $csv .= 'Quantas pessoas moram em seu domicílio, incluindo você?;';
    	    $csv .= 'Dessas pessoas, quantas trabalham?;';
    	    $csv .= 'Possui disponibilidade para mudança em virtude de oportunidades profissionais?;';
    	    $csv .= 'Como você avalia seu convívio familiar?;';
    	    $csv .= 'Seu domicílio é: ;';
    	    $csv .= 'Considerando você, quantas pessoas compartilham dessa renda?;';
    	    $csv .= 'Como você conheceu a WantU?'.PHP_EOL;
        }
	    
        $result['result'] = array_map( function($value) { 
            $return = str_replace([';'], '', $value); 
            $return = preg_replace( "/\r|\n/", "", $return);
            return $return;
        }, $result['result']);

        //echo '<pre>'; print_r($result); exit;
        //echo '<pre>'; print_r($result['result']); exit;
        
	    foreach ( $result['result'] as $row ){
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['nascimento'].';';
	        $csv .= $row['email'].';';
	        $csv .= $row['sexo'].';';
	        $csv .= $row['estado_civil'].';';
	        $csv .= $row['filhos'].';';
	        $csv .= $row['nacionalidade'].';';
	        $csv .= $row['cep_analise'].';';
	        $csv .= $row['endereco_analise'].';';
	        $csv .= $row['numero_analise'].';';
	        $csv .= $row['complemento_analise'].';';
	        $csv .= $row['bairro_analise'].';';
	        $csv .= $row['estado_analise'].';';
	        $csv .= $row['cidade_analise'].';';
	        $csv .= $row['telefone_residencial'].';';
	        $csv .= $row['telefone_celular'].';';
	        $csv .= $row['rg'].';';
	        $csv .= $row['orgao_emissor'].';';
	        $csv .= $row['carteira_de_trabalho'].';';
	        $csv .= $row['possui_graduacao'].';';
	        $csv .= $row['estado_da_instituicao_andamento'].';';
	        $csv .= $row['instituicao_de_ensino_andamento'].';';
	        $csv .= $row['nome_do_curso_andamento'].';';
	        $csv .= $row['tipo_do_curso_andamento'].';';
	        $csv .= $row['periodo_andamento'].';';
	        $csv .= $row['duracao_do_curso_andamento'].';';
	        $csv .= $row['horario_andamento'].';';
	        $csv .= $row['previsao_de_formatura_andamento'].';';
	        $csv .= $row['disponibilidade_estagio_andamento'].';';
	        $csv .= $row['coeficiente_de_rendimento_andamento'].';';
	        $csv .= $row['estado_da_instituicao_concluido'].';';
	        $csv .= $row['instituicao_de_ensino_concluido'].';';
	        $csv .= $row['nome_do_curso_concluido'].';';
	        $csv .= $row['tipo_do_curso_concluido'].';';
	        $csv .= $row['periodo_concluido'].';';
	        $csv .= $row['duracao_do_curso_concluido'].';';
	        $csv .= $row['horario_concluido'].';';
	        $csv .= $row['coeficiente_de_rendimento_concluido'].';';
	        $csv .= $row['conclusao_de_curso_concluido'].';';
	        $csv .= $row['possui_posgraduacao'].';';
	        $csv .= $row['tipo_pos'].';';
	        $csv .= $row['estado_da_instituicao_pos'].';';
	        $csv .= $row['instituicao_de_ensino_pos'].';';
	        $csv .= $row['nome_do_curso_pos'].';';
	        $csv .= $row['status_curso_pos'].';';
	        $csv .= $row['possui_cursos'].';';
	        $csv .= $row['tipo_de_curso'].';';
	        $csv .= $row['nome_do_curso'].';';
	        $csv .= $row['nivel_do_curso'].';';
	        $csv .= $row['carga_horaria'].';';
	        $csv .= $row['conclusao_curso'].';';
	        $csv .= $row['possui_idioma'].';';
	        $csv .= $row['qual_idioma'].';';
	        $csv .= $row['nivel_idioma'].';';
	        $csv .= $row['possui_experiencia_profissional'].';';
	        $csv .= $row['tipo_experiencia_profissional'].';';
	        $csv .= $row['qual_empresa'].';';
	        $csv .= $row['cargo'].';';
	        $csv .= $row['regime_de_contrato'].';';
	        $csv .= $row['data_de_inicio_empresarial'].';';
	        $csv .= $row['trabalho_atual'].';';
	        $csv .= $row['data_de_termino_empresarial'].';';
	        $csv .= $row['resumo_das_atividades'].';';
	        $csv .= $row['objetivos_profissionais'].';';
	        $csv .= $row['possui_experiencia_internacional'].';';
	        $csv .= $row['tipo_de_experiencia'].';';
	        $csv .= $row['tempo_de_duracao'].';';
	        $csv .= $row['pais_experiencia'].';';
	        $csv .= $row['possui_cnh'].';';
	        $csv .= $row['e_fumante'].';';
	        $csv .= $row['alguma_deficiencia'].';';
	        $csv .= $row['qual_deficiencia'].';';
	        $csv .= $row['mora_com_o_respnsalvel'].';';
	        $csv .= $row['possui_conta_bancaria'].';';
	        $csv .= $row['possui_cartao_de_credito'].';';
	        $csv .= $row['quantas_pessoas_moram_em_seu_domiciolio'].';';
	        $csv .= $row['quantos_pessoas_trabalham_em_seu_domiciolio'].';';
	        $csv .= $row['disponibilidade_internacional'].';';
	        $csv .= $row['como_voce_avalia_seu_convivio'].';';
	        $csv .= $row['seu_domiciolio_e'].';';
	        $csv .= $row['quantas_compartilham'].';';
	        $csv .= $row['como_voce_conheceu_a_wantu'].PHP_EOL;
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
        echo json_encode( $result['paginacao'] ); exit;
        
	}
	
	protected function downloadExcel_3( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelAptidoesProfissionais($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'aptidoes_profissionais.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            $result['result'][$key] = (array)($row);
	            $result['result'][$key]['sub_query'] = current($query);
	            
	            unset( $result['result'][$key]['sub_query']['id_aptidoes_profissionais'] );
	            unset( $result['result'][$key]['sub_query']['id_login'] );
	            unset( $result['result'][$key]['sub_query']['modificado'] );
	            unset( $result['result'][$key]['sub_query']['criado'] );
	            unset( $result['result'][$key]['sub_query']['nome'] );
	            
	            foreach ( $result['result'][$key]['sub_query'] as $key2=>$row2){
	                $result['result'][$key]['sub_query'][$key2] = json_decode($row2);
	            }
	            
	            //pontuações
	            $result['result'][$key]['sub_query']['pontuacoes'] = $model->getResultadoAptidaoProfissional($row['id_login']);
	            
	            
	        }
	        
	    }
	    
	    //  	    echo '<pre>'; print_r($result); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'Q1;';
	        $csv .= 'Q2;';
	        $csv .= 'Q3;';
	        $csv .= 'Q4;';
	        $csv .= 'Q5;';
	        $csv .= 'Q6;';
	        $csv .= 'Q7;';
	        $csv .= 'Q8;';
	        $csv .= 'Q9;';
	        $csv .= 'Q10;';
	        $csv .= 'Q11;';
	        $csv .= 'Q12;';
	        $csv .= 'Q13;';
	        $csv .= 'Q14;';
	        $csv .= 'Q15;';
	        $csv .= 'Q16;';
	        $csv .= 'Q17;';
	        $csv .= 'Q18;';
	        $csv .= 'Q19;';
	        $csv .= 'Q20;';
	        $csv .= 'Q21;';
	        $csv .= 'Q22;';
	        $csv .= 'Q23;';
	        $csv .= 'Q24;';
	        $csv .= 'Empate;';
	        $csv .= '1) Meu lema é “saber mais de menos”;';
	        $csv .= '2) Meu lema é “manda quem pode”;';
	        $csv .= '3) Meu lema é “sou dono do meu nariz”;';
	        $csv .= '4) Meu lema é “antes um pássaro na mão do que dois voando”;';
	        $csv .= '5) Meu lema é “pense fora da caixa” ;';
	        $csv .= '6) Meu lema é “se não for pra mudar o mundo, eu nem começo”;';
	        $csv .= '7) Meu lema é “perder uma batalha talvez, mas desistir da guerra jamais” ;';
	        $csv .= '8) Meu lema é “comigo é tudo junto e misturado ou então game over”;';
	        $csv .= '9) Meu lema é “minhas raízes estão no ar, minha casa é qualquer lugar”;'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        //echo '<pre>'; print_r($row['sub_query']['p18']->name); exit;
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= $row['sub_query']['p1']->name.';';
	        $csv .= $row['sub_query']['p2']->name.';';
	        $csv .= $row['sub_query']['p3'].';';
	        $csv .= $row['sub_query']['p4'].';';
	        $csv .= $row['sub_query']['p5'].';';
	        $csv .= $row['sub_query']['p6'].';';
	        $csv .= $row['sub_query']['p7'].';';
	        $csv .= $row['sub_query']['p8'].';';
	        $csv .= $row['sub_query']['p9'].';';
	        $csv .= $row['sub_query']['p10'].';';
	        $csv .= $row['sub_query']['p11'].';';
	        $csv .= $row['sub_query']['p12'].';';
	        $csv .= $row['sub_query']['p13'].';';
	        $csv .= $row['sub_query']['p14'].';';
	        $csv .= $row['sub_query']['p15'].';';
	        $csv .= $row['sub_query']['p16'].';';
	        $csv .= $row['sub_query']['p17']->name.';';
	        $csv .= $row['sub_query']['p18']->name.';';
	        $csv .= $row['sub_query']['p19'].';';
	        $csv .= $row['sub_query']['p20'].';';
	        $csv .= $row['sub_query']['p21'].';';
	        $csv .= $row['sub_query']['p22']->name.';';
	        $csv .= $row['sub_query']['p23'].';';
	        $csv .= $row['sub_query']['p24']->name.';';
	        $csv .= $row['sub_query']['p25']->name.';';
	        $csv .= $row['sub_query']['pontuacoes']['Especialista']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Gerencial']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Autonomia']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Seguranca']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Empreendedorismo']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Dedicacao']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Desafio']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Estilo']['porcentagem'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['Internacional']['porcentagem'].'%'.PHP_EOL;
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	
	
	protected function downloadExcel_4( $qry = [], $paginacao = [] )
	{
	     
	    $model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
	     
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	         
	        //sub query
	        $query = $model->get( ['expr'=>'inteligencias_multiplas.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            $result['result'][$key] = (array)($row);
	            $result['result'][$key]['sub_query'] = current($query);
	        
	            unset( $result['result'][$key]['sub_query']['id_inteligencias_multiplas'] );
	            unset( $result['result'][$key]['sub_query']['id_login'] );
	            unset( $result['result'][$key]['sub_query']['modificado'] );
	            unset( $result['result'][$key]['sub_query']['criado'] );
	            unset( $result['result'][$key]['sub_query']['nome'] );
	            
	            //pontuações
	            $pontuacoes = (object)$result['result'][$key]['sub_query'];
	            $result['result'][$key]['3maiores'] = $model->todasPontuacoes($pontuacoes);
	            
    	        //decode
    	        foreach ( $result['result'][$key]['sub_query'] as $subKey => $subQuery ) {
    	            
    	            $result['result'][$key]['sub_query'][$subKey] = [];
    	            
    	            if ( strlen($subQuery) > 0 ){
    	               $decode = json_decode($subQuery);
    	               $result['result'][$key]['sub_query'][$subKey]['tempo'] = $decode->tempo;
    	               $result['result'][$key]['sub_query'][$subKey]['value'] = $decode->value;
    	            }
    	        }
	        }

	    }
	    
	    
	    //echo '<pre>'; print_r($result['result']); exit;
	    
	    $csv = '';
	     
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'Q1;';
	        $csv .= 'T1;';
	        $csv .= 'Q2;';
	        $csv .= 'T2;';
	        $csv .= 'Q3;';
	        $csv .= 'T3;';
	        $csv .= 'Q4;';
	        $csv .= 'T4;';
	        $csv .= 'Q5;';
	        $csv .= 'T5;';
	        $csv .= 'Q6;';
	        $csv .= 'T6;';
	        $csv .= 'Q7;';
	        $csv .= 'T7;';
	        $csv .= 'Q8;';
	        $csv .= 'T8;';
	        $csv .= 'Q9;';
	        $csv .= 'T9;';
	        $csv .= 'Q10;';
	        $csv .= 'T10;';
	        $csv .= 'Q11;';
	        $csv .= 'T11;';
	        $csv .= 'Q12;';
	        $csv .= 'T12;';
	        $csv .= 'Q13;';
	        $csv .= 'T13;';
	        $csv .= 'Q14;';
	        $csv .= 'T14;';
	        $csv .= 'Q15;';
	        $csv .= 'T15;';
	        $csv .= 'Q16;';
	        $csv .= 'T16;';
	        $csv .= 'Q17;';
	        $csv .= 'T17;';
	        $csv .= 'Q18;';
	        $csv .= 'T18;';
	        $csv .= 'Matemática;';
	        $csv .= 'Linguística;';
	        $csv .= 'Espacial;';
	        $csv .= 'Interpessoal;';
	        $csv .= 'Intrapessoal;';
	        $csv .= 'Naturalista;';
	        $csv .= 'Existencial;';
	        $csv .= 'Musical;';
	        $csv .= 'Corporal;'.PHP_EOL;
	    }
	     
	    foreach ( $result['result'] as $row ){
	         
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= $row['sub_query']['p1']['value'].';';
	        $csv .= $row['sub_query']['p1']['tempo'].';';
	        $csv .= $row['sub_query']['p2']['value'].';';
	        $csv .= $row['sub_query']['p2']['tempo'].';';
	        $csv .= $row['sub_query']['p3']['value'].';';
	        $csv .= $row['sub_query']['p3']['tempo'].';';
	        $csv .= $row['sub_query']['p4']['value'].';';
	        $csv .= $row['sub_query']['p4']['tempo'].';';
	        $csv .= $row['sub_query']['p5']['value'].';';
	        $csv .= $row['sub_query']['p5']['tempo'].';';
	        $csv .= $row['sub_query']['p6']['value'].';';
	        $csv .= $row['sub_query']['p6']['tempo'].';';
	        $csv .= $row['sub_query']['p7']['value'].';';
	        $csv .= $row['sub_query']['p7']['tempo'].';';
	        $csv .= $row['sub_query']['p8']['value'].';';
	        $csv .= $row['sub_query']['p8']['tempo'].';';
	        $csv .= $row['sub_query']['p9']['value'].';';
	        $csv .= $row['sub_query']['p9']['tempo'].';';
	        $csv .= $row['sub_query']['p10']['value'].';';
	        $csv .= $row['sub_query']['p10']['tempo'].';';
	        $csv .= $row['sub_query']['p11']['value'].';';
	        $csv .= $row['sub_query']['p11']['tempo'].';';
	        $csv .= $row['sub_query']['p12']['value'].';';
	        $csv .= $row['sub_query']['p12']['tempo'].';';
	        $csv .= $row['sub_query']['p13']['value'].';';
	        $csv .= $row['sub_query']['p13']['tempo'].';';
	        $csv .= $row['sub_query']['p14']['value'].';';
	        $csv .= $row['sub_query']['p14']['tempo'].';';
	        $csv .= $row['sub_query']['p15']['value'].';';
	        $csv .= $row['sub_query']['p15']['tempo'].';';
	        $csv .= $row['sub_query']['p16']['value'].';';
	        $csv .= $row['sub_query']['p16']['tempo'].';';
	        $csv .= $row['sub_query']['p17']['value'].';';
	        $csv .= $row['sub_query']['p17']['tempo'].';';
	        $csv .= $row['sub_query']['p18']['value'].';';
	        $csv .= $row['sub_query']['p18']['tempo'].';';
	        $csv .= $row['3maiores']['Logico-matematico'].';';
	        $csv .= $row['3maiores']['Linguistica'].';';
	        $csv .= $row['3maiores']['Espacial'].';';
	        $csv .= $row['3maiores']['Interpessoal'].';';
	        $csv .= $row['3maiores']['Intrapessoal'].';';
	        $csv .= $row['3maiores']['Naturalista'].';';
	        $csv .= $row['3maiores']['Existencial'].';';
	        $csv .= $row['3maiores']['Musical'].';';
	        $csv .= $row['3maiores']['Corporal'].PHP_EOL;
	        
	         
	    }
	    
	    $csv = utf8_decode($csv);
	     
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	     
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	
	}
	
	protected function downloadExcel_5( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelPontosFortes($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'pontos_fortes.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            
	            $result['result'][$key] = (array)($row);
	            
	            foreach ( $query as $keyQuery => $rowQuery ){
	                $result['result'][$key]['sub_query'][$keyQuery] = $rowQuery;
	            }
	            
	        }
	        
	    }
	    
	    foreach( $result['result'] as &$row )
	    {
	        $row['sub_query'] = array_column($row['sub_query'], null, "key");
	    }
	    
	    //echo '<pre>'; print_r($result); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'F1;';
	        $csv .= 'F2;';
	        $csv .= 'F3;';
	        $csv .= 'F4;';
	        $csv .= 'F5;';
	        $csv .= 'F6;';
	        $csv .= 'F7;';
	        $csv .= 'F8;';
	        $csv .= 'F9;';
	        $csv .= 'F10;';
	        $csv .= 'F11;';
	        $csv .= 'F12;';
	        $csv .= 'F13;';
	        $csv .= 'F14;';
	        $csv .= 'F15;';
	        $csv .= 'F16;';
	        $csv .= 'F17;';
	        $csv .= 'F18;';
	        $csv .= 'F19;';
	        $csv .= 'F20;';
	        $csv .= 'F21;';
	        $csv .= 'F22;';
	        $csv .= 'F23;';
	        $csv .= 'F24;';
	        $csv .= 'F25;';
	        $csv .= 'F26;';
	        $csv .= 'F27;';
	        $csv .= 'F28;';
	        $csv .= 'F29;';
	        $csv .= 'F30;';
	        $csv .= 'F31;';
	        $csv .= 'F32;';
	        $csv .= 'F33;';
	        $csv .= 'F34'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        //echo '<pre>'; print_r($row['sub_query']['p18']->name); exit;
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= count($row['sub_query']['0']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['1']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['2']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['3']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['4']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['5']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['6']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['7']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['8']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['9']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['10']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['11']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['12']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['13']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['14']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['15']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['16']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['17']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['18']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['19']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['20']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['21']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['22']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['23']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['24']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['25']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['26']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['27']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['28']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['29']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['30']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['31']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['32']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['33']) == 0 ? '0;'.PHP_EOL : '1;'.PHP_EOL;
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	
	protected function downloadExcel_6( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelCompetencias($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'competencias.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            $result['result'][$key] = (array)($row);
	            $result['result'][$key]['sub_query'] = current($query);
	            
	            unset( $result['result'][$key]['sub_query']['id_competencias'] );
	            unset( $result['result'][$key]['sub_query']['id_login'] );
	            unset( $result['result'][$key]['sub_query']['modificado'] );
	            unset( $result['result'][$key]['sub_query']['criado'] );
	            unset( $result['result'][$key]['sub_query']['nome'] );
	            
	        }
	        
	    }
	    
	    
	    //echo '<pre>'; print_r($result); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'AUTODESENVOLVIMENTO E GESTÃO DO CONHECIMENTO;';
	        $csv .= 'CAPACIDADE DE ADAPTAÇÃO E FLEXIBILIDADE;';
	        $csv .= 'CAPACIDADE EMPREENDEDORA;';
	        $csv .= 'CAPACIDADE NEGOCIAL;';
	        $csv .= 'COMUNICAÇÃO E INTERAÇÃO;';
	        $csv .= 'CRIATIVIDADE E INOVAÇÃO;';
	        $csv .= 'CULTURA DA QUALIDADE;';
	        $csv .= 'LIDERANÇA;';
	        $csv .= 'MOTIVAÇÃO E ENERGIA PARA O TRABALHO;';
	        $csv .= 'ORIENTAÇÃO PARA RESULTADOS;';
	        $csv .= 'PLANEJAMENTO E ORGANIZAÇÃO;';
	        $csv .= 'RELACIONAMENTO INTERPESSOAL;';
	        $csv .= 'TOMADA DE DECISÃO;';
	        $csv .= 'TRABALHO EM EQUIPE;';
	        $csv .= 'VISÃO SISTÊMICA;'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= $row['sub_query']['p1'].';';
	        $csv .= $row['sub_query']['p2'].';';
	        $csv .= $row['sub_query']['p3'].';';
	        $csv .= $row['sub_query']['p4'].';';
	        $csv .= $row['sub_query']['p5'].';';
	        $csv .= $row['sub_query']['p6'].';';
	        $csv .= $row['sub_query']['p7'].';';
	        $csv .= $row['sub_query']['p8'].';';
	        $csv .= $row['sub_query']['p9'].';';
	        $csv .= $row['sub_query']['p10'].';';
	        $csv .= $row['sub_query']['p11'].';';
	        $csv .= $row['sub_query']['p12'].';';
	        $csv .= $row['sub_query']['p13'].';';
	        $csv .= $row['sub_query']['p14'].';';
	        $csv .= $row['sub_query']['p15'].PHP_EOL;
	        
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	
	protected function indicePerdidice( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelAnaliseEntrevista($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'analiseEntrevista.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            $result['result'][$key] = (array)($row);
	            $result['result'][$key]['sub_query'] = current($query);
	            
	            unset( $result['result'][$key]['sub_query']['id_analise_entrevista'] );
	            unset( $result['result'][$key]['sub_query']['id_login'] );
	            unset( $result['result'][$key]['sub_query']['modificado'] );
	            unset( $result['result'][$key]['sub_query']['criado'] );
	            unset( $result['result'][$key]['sub_query']['nome'] );
	            
	        }
	        
	    }
	    
	    
	    //echo '<pre>'; print_r($result); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'Onde estou?;';
	        $csv .= 'Q1;';
	        $csv .= 'Q2;';
	        $csv .= 'Q3;';
	        $csv .= 'Para onde vou?;';
	        $csv .= 'Q1;';
	        $csv .= 'Q2;';
	        $csv .= 'Q3;';
	        $csv .= 'Q4;';
	        $csv .= 'Q5;';
	        $csv .= 'Como faço para chegar lá?;';
	        $csv .= 'Q1;';
	        $csv .= 'Q2;';
	        $csv .= 'Q3;';
	        $csv .= 'Q4;';
	        $csv .= 'Q5;';
	        $csv .= 'Q6;';
	        $csv .= 'Q7;';
	        $csv .= 'Q8;';
	        $csv .= 'Q9;';
	        $csv .= 'Q10;';
	        $csv .= 'Índice geral;'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= (int)$row['sub_query']['total_onde_estou'].'%'.';';
	        $csv .= $row['sub_query']['p1'].$row['sub_query']['p2'].$row['sub_query']['p3'].';';
	        $csv .= $row['sub_query']['p4'].$row['sub_query']['p5'].$row['sub_query']['p6'].';';
	        $csv .= $row['sub_query']['p7'].$row['sub_query']['p8'].$row['sub_query']['p9'].';';
	        $csv .= (int)$row['sub_query']['total_para_onde_vou'].'%'.';';
	        $csv .= $row['sub_query']['p10'].$row['sub_query']['p11'].$row['sub_query']['p12'].';';
	        $csv .= $row['sub_query']['p13'].$row['sub_query']['p14'].$row['sub_query']['p15'].';';
	        $csv .= $row['sub_query']['p16'].$row['sub_query']['p17'].$row['sub_query']['p18'].';';
	        $csv .= $row['sub_query']['p19'].$row['sub_query']['p20'].$row['sub_query']['p21'].';';
	        $csv .= $row['sub_query']['p22'].$row['sub_query']['p23'].$row['sub_query']['p24'].';';
	        $csv .= (int)$row['sub_query']['total_como_faco_para_chegar'].'%'.';';
	        $csv .= $row['sub_query']['p25'].$row['sub_query']['p26'].$row['sub_query']['p27'].';';
	        $csv .= $row['sub_query']['p28'].$row['sub_query']['p29'].$row['sub_query']['p30'].';';
	        $csv .= $row['sub_query']['p31'].$row['sub_query']['p32'].$row['sub_query']['p33'].';';
	        $csv .= $row['sub_query']['p34'].$row['sub_query']['p35'].$row['sub_query']['p36'].';';
	        $csv .= $row['sub_query']['p37'].$row['sub_query']['p38'].$row['sub_query']['p39'].';';
	        $csv .= $row['sub_query']['p40'].$row['sub_query']['p41'].$row['sub_query']['p42'].';';
	        $csv .= $row['sub_query']['p43'].$row['sub_query']['p44'].$row['sub_query']['p45'].';';
	        $csv .= $row['sub_query']['p46'].$row['sub_query']['p47'].$row['sub_query']['p48'].';';
	        $csv .= $row['sub_query']['p49'].$row['sub_query']['p50'].$row['sub_query']['p51'].';';
	        $csv .= $row['sub_query']['p52'].$row['sub_query']['p53'].$row['sub_query']['p54'].';';
	        $csv .= (int)$row['sub_query']['total_indice_de_perdidice'].'%'.PHP_EOL;
	        
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	
	protected function resultadosFinais( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelResultadosFinaisCompleto($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'resultadosFinaisCompleto.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            $result['result'][$key] = (array)($row);
	            $result['result'][$key]['sub_query'] = current($query);
	            
	            unset( $result['result'][$key]['sub_query']['id_resultados_finais_completo'] );
	            unset( $result['result'][$key]['sub_query']['id_login'] );
	            unset( $result['result'][$key]['sub_query']['modificado'] );
	            unset( $result['result'][$key]['sub_query']['criado'] );
	            unset( $result['result'][$key]['sub_query']['nome'] );
	            
	        }
	        
	    }
	    
	    
	    //echo '<pre>'; print_r($result); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'AUTODESENVOLVIMENTO E GESTÃO DO CONHECIMENTO;';
	        $csv .= 'CAPACIDADE DE ADAPTAÇÃO E FLEXIBILIDADE;';
	        $csv .= 'CAPACIDADE EMPREENDEDORA;';
	        $csv .= 'CAPACIDADE NEGOCIAL;';
	        $csv .= 'COMUNICAÇÃO E INTERAÇÃO;';
	        $csv .= 'CRIATIVIDADE E INOVAÇÃO;';
	        $csv .= 'CULTURA DA QUALIDADE;';
	        $csv .= 'LIDERANÇA;';
	        $csv .= 'MOTIVAÇÃO E ENERGIA PARA O TRABALHO;';
	        $csv .= 'ORIENTAÇÃO PARA RESULTADOS;';
	        $csv .= 'PLANEJAMENTO E ORGANIZAÇÃO;';
	        $csv .= 'RELACIONAMENTO INTERPESSOAL;';
	        $csv .= 'TOMADA DE DECISÃO;';
	        $csv .= 'TRABALHO EM EQUIPE;';
	        $csv .= 'VISÃO SISTÊMICA;';
	        $csv .= 'Acadêmica;';
	        $csv .= 'Gerencial;';
	        $csv .= 'Empreendedora;';
	        $csv .= 'Pública;';
	        $csv .= 'Especialista;';
	        $csv .= 'Política;'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= $row['sub_query']['cp1'].'%'.';';
	        $csv .= $row['sub_query']['cp2'].'%'.';';
	        $csv .= $row['sub_query']['cp3'].'%'.';';
	        $csv .= $row['sub_query']['cp4'].'%'.';';
	        $csv .= $row['sub_query']['cp5'].'%'.';';
	        $csv .= $row['sub_query']['cp6'].'%'.';';
	        $csv .= $row['sub_query']['cp7'].'%'.';';
	        $csv .= $row['sub_query']['cp8'].'%'.';';
	        $csv .= $row['sub_query']['cp9'].'%'.';';
	        $csv .= $row['sub_query']['cp10'].'%'.';';
	        $csv .= $row['sub_query']['cp11'].'%'.';';
	        $csv .= $row['sub_query']['cp12'].'%'.';';
	        $csv .= $row['sub_query']['cp13'].'%'.';';
	        $csv .= $row['sub_query']['cp14'].'%'.';';
	        $csv .= $row['sub_query']['cp15'].'%'.';';
	        $csv .= $row['sub_query']['academica'].'%'.';';
	        $csv .= $row['sub_query']['gerencial'].'%'.';';
	        $csv .= $row['sub_query']['empreendedora'].'%'.';';
	        $csv .= $row['sub_query']['publica'].'%'.';';
	        $csv .= $row['sub_query']['especialista'].'%'.';';
	        $csv .= $row['sub_query']['politica'].'%'.PHP_EOL;
	        
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	

	protected function downloadExcel_2( $qry = [], $paginacao = [] )
	{
	    
	    $model = new ModelPerfilComportamental($this->tb, $this->adapter);
	    
	    $result = [];
	    $result['paginacao'] = $paginacao;
	    $result['result'] = [];
	    
	    foreach ( $qry as $key => $row ){
	        
	        //sub query
	        $query = $model->get( ['expr'=>'perfilComportamental.id_login = "'.$row['id_login'].'"'] )->toArray();
	        
	        if ( count($query) )
	        {
	            
	            $result['result'][$key] = (array)($row);
	            
	            foreach ( $query as $keyQuery => $rowQuery ){
	                $result['result'][$key]['sub_query'][$keyQuery] = $rowQuery;
	            }
	            
	        }
	        
	    }
	    
	    

	    
	    
	    
	    foreach( $result['result'] as &$row )
	    {
	        $row['sub_query'] = array_column($row['sub_query'], null, "key");
	        $row['sub_query']['pontuacoes'] = $model->resultado($row['sub_query']);
	    }
	    
	    //echo '<pre>'; print_r($row['sub_query']['pontuacoes']); exit;
	    
	    $csv = '';
	    
	    if ( $_GET['page_10'] == 1 ){
	        $csv .= 'Nome;';
	        $csv .= 'CPF;';
	        $csv .= 'Curioso;';
	        $csv .= 'Gosta de privacidade;';
	        $csv .= 'Racional;';
	        $csv .= 'Reservado;';
	        $csv .= 'Sabe se preservar;';
	        $csv .= 'Antecipa necessidades;';
	        $csv .= 'Facilmente entediado;';
	        $csv .= 'Impulsivo;';
	        $csv .= 'Suave, tem tato;';
	        $csv .= 'Preocupa-se com os outros;';
	        $csv .= 'Assertivo;';
	        $csv .= 'Assume riscos;';
	        $csv .= 'Confiante;';
	        $csv .= 'Corajoso;';
	        $csv .= 'Independente;';
	        $csv .= 'Construtor de confiança;';
	        $csv .= 'Esquiva-se de conflito;';
	        $csv .= 'Mediador;';
	        $csv .= 'Persuasivo;';
	        $csv .= 'Paciente;';
	        $csv .= 'Bom comunicador;';
	        $csv .= 'Diz a coisa certa;';
	        $csv .= 'Espontâneo;';
	        $csv .= 'Gosta de pessoas;';
	        $csv .= 'Senso de Humor;';
	        $csv .= 'Criador de Alternativas;';
	        $csv .= 'Criativo;';
	        $csv .= 'Gosta de variedade;';
	        $csv .= 'Inovador;';
	        $csv .= 'Extrovertido;';
	        $csv .= 'Alto nível de exigência;';
	        $csv .= 'Meticuloso;';
	        $csv .= 'Obedece as regras;';
	        $csv .= 'Orientado para detalhes;';
	        $csv .= 'Perfeccionista;';
	        $csv .= 'Influenciador;';
	        $csv .= 'Líder;';
	        $csv .= 'Mentor;';
	        $csv .= 'Positivo;';
	        $csv .= 'Toma a dianteira;';
	        $csv .= 'Alma da festa;';
	        $csv .= 'Animado;';
	        $csv .= 'Cheio de energia;';
	        $csv .= 'Energético;';
	        $csv .= 'Rápido;';
	        $csv .= 'Ambicioso;';
	        $csv .= 'Competititvo;';
	        $csv .= 'Determinado;';
	        $csv .= 'Objetivo;';
	        $csv .= 'Orientado para resultados;';
	        $csv .= 'Controlador;';
	        $csv .= 'Metódico;';
	        $csv .= 'Organizado;';
	        $csv .= 'Planeja com antecedência;';
	        $csv .= 'Previsível;';
	        $csv .= 'Agradável;';
	        $csv .= 'Amigável;';
	        $csv .= 'Simpático;';
	        $csv .= 'Sociável;';
	        $csv .= 'Leal;';
	        $csv .= 'Considera as opções;';
	        $csv .= 'Bem informado;';
	        $csv .= 'Cuidadoso;';
	        $csv .= 'Decidido;';
	        $csv .= 'Forte opinião;';
	        $csv .= 'Empático;';
	        $csv .= 'Compreensivo;';
	        $csv .= 'Cooperativo;';
	        $csv .= 'Fácil de lidar;';
	        $csv .= 'Comprometido;';
	        $csv .= 'Cauteloso na ação;';
	        $csv .= 'Não orientado para detalhes;';
	        $csv .= 'Ordeiro;';
	        $csv .= 'Percebe o quadro geral;';
	        $csv .= 'Sistemático;';
	        $csv .= 'Mão na Massa;';
	        $csv .= 'Cheguei Chegando;';
	        $csv .= 'Fala que eu te escuto;';
	        $csv .= 'Só sei que nada sei'.PHP_EOL;
	    }
	    
	    foreach ( $result['result'] as $row ){
	        
	        //echo '<pre>'; print_r($result['result']); exit;
	        
	        $csv .= $row['nome'].';';
	        $csv .= $row['cpf'].';';
	        $csv .= count($row['sub_query']['0']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['1']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['2']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['3']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['4']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['5']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['6']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['7']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['8']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['9']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['10']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['11']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['12']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['13']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['14']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['15']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['16']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['17']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['18']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['19']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['20']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['21']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['22']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['23']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['24']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['25']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['26']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['27']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['28']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['29']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['30']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['31']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['32']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['33']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['34']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['35']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['36']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['37']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['38']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['39']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['40']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['41']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['42']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['43']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['44']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['45']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['46']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['47']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['48']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['49']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['50']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['51']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['52']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['53']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['54']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['55']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['56']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['57']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['58']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['59']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['60']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['61']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['62']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['63']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['64']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['65']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['66']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['67']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['68']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['69']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['70']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['71']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['72']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['73']) == 0 ? '0;' : '1;';
	        $csv .= count($row['sub_query']['74']) == 0 ? '0;' : '1;';
	        $csv .= $row['sub_query']['pontuacoes']['executor'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['comunicador'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['planejador'].'%'.';';
	        $csv .= $row['sub_query']['pontuacoes']['analiista'].'%'.PHP_EOL;
	        
	    }
	    
	    $csv = utf8_decode($csv);
	    
	    //criacao do fOpen
	    $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/assets/downloads/'.$_POST['arquivo'].'.csv', 'a+');
	    fwrite($fp, $csv);
	    fclose($fp);
	    
	    header('Content-Type: application/json');
	    echo json_encode( $result['paginacao'] ); exit;
	    
	}
	
	
}