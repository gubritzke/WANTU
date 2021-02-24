<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Zend\Form\Element\Email;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelPerfilComportamental;
use Application\Model\ModelInteligenciasMultiplas;
use Application\Model\ModelPontosFortes;
use Application\Model\ModelCompetencias;
use Application\Model\ModelAnaliseCursos;
use Application\Model\ModelAnaliseDeCurriculo;
use Application\Model\ModelAnaliseExperienciaProfissional;
use Application\Model\ModelDadosEscolares;
use Application\Model\ModelPosGraduacao;
use Application\Model\ModelAnaliseExperienciaInternacional;
use Application\Model\ModelIdiomas;
use Application\Model\ModelPlanosUsuario;
use Application\Classes\Relatorio;
use Application\Model\ModelAnaliseEntrevista;

class MinhaContaController extends GlobalController
{
	
	private function init()
	{
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$prog = $this->view['data_user'] = $model->get($filter)->current();
		$this->view['data_user'] = $model->get($filter)->current();
		
		//echo '<pre>'; print_r($this->view['data_user']); exit;
		
		$avanco = '0';
		$feitos = '0';
		$comp = '9';
		
		if ( $prog['analise_de_curriculo'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		if ( $prog['perfil_comportamental'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		if ( $prog['aptidoes_profissionais'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		if ( $prog['inteligencias_multiplas'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		if ( $prog['pontos_fortes'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		if ( $prog['competencias'] == 'Completo'){
			$avanco = $avanco + $comp;
			$feitos = $feitos + '1';
		}
		
		if ( $prog['perfil_comportamental'] == 'Completo' && $prog['aptidoes_profissionais'] == 'Completo' && $prog['inteligencias_multiplas'] == 'Completo' && $prog['pontos_fortes'] == 'Completo' && $prog['competencias'] == 'Completo' ){
		    $avanco = $avanco + $comp;
		    $feitos = $feitos + '1';
		}
		
		if ( !empty($prog['file_pdi'])){
		    $avanco = $avanco + $comp;
		    $feitos = $feitos + '1';
		}
		
		
		
		
		
		$this->view['feitos'] = $feitos;
		$this->view['avanco'] = $avanco;
	}
	
	private function init2()
	{
		
		$user = new ModelLogin($this->tb, $this->adapter);
		
		$filter = [];
		$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
		$get = $user->get($filter)->toArray()[0];
		
		// 		if ( $get['analise_de_curriculo'] == 'Completo' ) {
		// 			return $this->redirect()->toUrl('/analise/entrevista');
		// 		}
		
		try
		{
			
			$get = $this->params()->fromQuery();
			
			$modelAnalise = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
			
			//code = id_analise_de_curriculo
			
			//valida se existe um id
			if ( empty($get['code']) ) {
				
				//seleciona os dados do formulario do usuario
				$filter = array();
				$filter['expr'] = 'status = "incompleto"';
				$filter['expr'] .= ' AND analiseDeCurriculo.id_login = "' . $this->layout()->me->id_login. '"';
				$this->view['data'] = $modelAnalise->get($filter);
				
				if ( count( $this->view['data'] ) > 0 ) {
					
					$this->view['data'] = $this->view['data']->current();
					$this->redirect()->toUrl($_SERVER['REQUEST_URI'].'?code='.base64_encode( $this->view['data']->id_analise_de_curriculo));
					
				}
				
			} else {
				
				$id_analise_de_curriculo = base64_decode( $get['code'] );
				$this->id = $id_analise_de_curriculo;
				$filter = [];
				$filter['expr'] .= ' id_analise_de_curriculo = "'.$id_analise_de_curriculo.'" AND analiseDeCurriculo.id_login = "' . $this->layout()->me->id_login. '"';
				$this->view['data'] = $modelAnalise->get($filter, ['id_analise_de_curriculo' => $id_analise_de_curriculo]);
				
				if ( count( $this->view['data'] ) == 0 ) {
					
					throw new \Exception('erro');
					
				} else {
					
					// 					echo '<pre>'; print_r( $this->view['data'] ); exit;
					$this->view['data'] = $this->view['data']->current();
					//echo '<pre>'; print_r($this->view['data']); exit;
					
				}
				
			}
			
			if (empty($this->layout()->me->id_login)){
				throw new \Exception('Para acessar essa página faça o login');
			}
			
			//call actions
			$method = $this->params()->fromPost('method');
			if( method_exists($this, $method) ) $this->$method();
			
		}
		catch(\Exception $e)
		{
			
			$this->flashMessenger()->addErrorMessage($e->getMessage());
			$this->redirect()->toUrl('/');
			
		}
		
	}
	
	public function indexAction()
	{
		$this->init();
		
		$breadcrumb = [];
		$breadcrumb['Inicio'] = 'https://wantu.com.br';
		$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
		$this->layout()->setVariable('breadcrumb', $breadcrumb);
		
		//SELECIONA PERFIL
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, true);
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login . '"';
		$modelanalise = new ModelAnaliseEntrevista($this->tb, $this->adapter);
		$this->view['analise'] = $modelanalise->get($filter)->current();
		
		//SELECIONA O PLANO
// 		$filter = array();
// 		$filter['expr'] = 'id_login = "'. $this->layout()->me->id_login.'"';
// 		$filter['expr'] .=' AND status = "1"';
// 		$filter['order'] = 'id_plano DESC';
// 		$model = new ModelPlanosUsuario($this->tb, $this->adapter);
// 		$plano_user = $model->get($filter)->current();
		 
// 		if ($plano_user->id_plano == '1' ){
// 			$this->view['plano1'] = 1;
// 		}
// 		if ($plano_user->id_plano == '2' ){
// 			$this->view['plano2'] = 1;
// 		}
// 		if ($plano_user->id_plano == '3' ){
// 			$this->view['plano3'] = 1;
// 		}
		
		
		//echo '<pre>'; print_r($plano_user); exit;
		
		//view
		$this->head->addCarousel();
		$this->head->setJs('carousel.js');
		$this->head->setTitle('Minha Conta');
		
		//echo '<pre>'; print_r($this->layout()->me->id_login); exit;
		
		return new ViewModel($this->view);
	}
	
	public function perfilAction()
	{
		$this->init();
		
		$breadcrumb = [];
		$breadcrumb['Inicio'] = 'https://wantu.com.br';
		$breadcrumb['Minha conta'] = '/minha-conta';
		$breadcrumb['Perfil'] = 'https://wantu.com.br/minha-conta/perfil';
		$this->layout()->setVariable('breadcrumb', $breadcrumb);
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['user'] = $model->get($filter)->current();
		
		$this->head->setTitle('Perfil');
		
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		
		return new ViewModel($this->view);
	}
	
	public function uploadAction()
	{
		@$upload = new \Naicheframework\Upload\Upload();
		@$uploadResult = $upload->setExtensions(['png','jpg','jpeg','pdf'])->file($_FILES['foto'], "user");
		if( $uploadResult === false ) die('error');
		
		$img = $upload->getFilenameCurrent();
		
		die($img);
	}
	
	public function curriculoAction()
	{
		$this->init();
		$this->init2();
		
		//echo '<pre>'; print_r($this->id); exit;
		
		//seleciona os dados do usuario
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['usuario'] = $model->get($filter)->toArray();
		
		//selecionar info complementares
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
		$this->view['info_complementares'] = $model->get($filter)->current();
		
		//selecionar experiencia profissional
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['order'] = 'data_de_inicio_empresarial DESC';
		$filter['expr'] .= ' AND status != "Excluido"';
		$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
		$this->view['profissional'] = $model->get($filter)->toArray();
		
		//echo '<pre>'; print_r($this->view['profissional']); exit;
		
		//selecionar cursos
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Curso"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_cursos'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Minicurso"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_minicurso'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Workshop"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_workshop'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Outro"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_outro'] = $modelCursos->get($filter)->toArray();
		
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$filter['expr'] .= ' AND tipo_de_curso = "Evento"';
		$filter['order'] = 'conclusao_curso DESC';
		$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
		$this->view['cursos_evento'] = $modelCursos->get($filter)->toArray();
		
		//echo '<pre>'; print_r($this->view['cursos_evento']); exit;
		
		//selecionar experiencia internacional
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
		$this->view['internacional'] = $modelCursos->get($filter)->toArray();
		
		//selecionar dados escolares
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelDadosEscolares($this->tb, $this->adapter);
		$this->view['dados_escolares'] = $modelCursos->get($filter)->toArray();
		
		//selecionar Pos
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelPosGraduacao($this->tb, $this->adapter);
		$this->view['dados_pos'] = $modelCursos->get($filter)->toArray();
		
		//selecionar idiomas
		$filter = array();
		$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
		$filter['expr'] .= ' AND status != "Excluido"';
		$modelCursos = new ModelIdiomas($this->tb, $this->adapter);
		$this->view['dados_idiomas'] = $modelCursos->get($filter)->toArray();
		
		//echo '<pre>'; print_r($this->view['data']); exit;
		
		
		return new ViewModel($this->view);
	}
	
	public function trilha2RelatorioAction()
	{
		
		//definir id do login
		$this->view['id_login'] = $id_login = $this->params('id');
		
		$filter = array();
		$filter['expr'] = 'perfil_comportamental = "Completo"';
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		
		//selecionar respostas
		$filter = array();
		$filter['expr'] = 'perfilComportamental.id_login = "' . $id_login . '"';
		$model = new ModelPerfilComportamental($this->tb, $this->adapter);
		$this->view['result'] = $model->get($filter)->toArray();
		$this->view['resultado'] = $model->resultado( $this->view['result']);
		$this->view['result'] = (object) current($this->view['result']);
		
		//echo '<pre>'; print_r( $this->view['result']); exit;
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function trilha3RelatorioAction()
	{
		$this->view['id_login'] = $id_login = $this->params('id');
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		
		$aptidao = new ModelAptidoesProfissionais($this->tb, $this->adapter);
		$this->view['result'] = $aptidao->getResultadoAptidaoProfissional($id_login);
		$this->view['tres'] = $aptidao->tresMaioresPontuacoes($this->view['result']);
		
		//echo '<pre>'; print_r($this->view['tres']); exit;
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function trilha4RelatorioAction()
	{
		$this->view['id_login'] = $id_login = $this->params('id');
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		
		//select na inteligencia
		$where = "inteligencias_multiplas.id_login = '$id_login'";
		$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->current();
		$result = $model->tresMaioresPontuacoes($result);
		$result = array_keys($result);
		$this->view['tres'] = $result;
		
		//echo '<pre>'; print_r($this->view['tres']); exit;
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function trilha5RelatorioAction()
	{
		
		//definir id do login
		$this->view['id_login'] = $id_login = $this->params('id');
		
		//echo '<pre>'; print_r($this->view['id_login']); exit;
		
		$filter = array();
		$filter['expr'] = 'pontos_fortes = "Completo"';
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		
		//selecionar respostas
		$where = "pontos_fortes.id_login = '$id_login'";
		$model = new ModelPontosFortes($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->toArray();
		$result = array_column($result, 'value');
		$this->view['result'] = $result;
		
		
		//echo '<pre>'; print_r( $this->view['result']); exit;
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function trilha6RelatorioAction()
	{
		
		//definir id do login
		$this->view['id_login'] = $id_login = $this->params('id');
		
		//echo '<pre>'; print_r($this->view['id_login']); exit;
		
		$filter = array();
		$filter['expr'] = 'pontos_fortes = "Completo"';
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		
		//echo '<pre>'; print_r($this->view['perfil']); exit;
		
		//selecionar respostas
		$filter['expr']= "login.id_login = '$id_login'";
		$model = new ModelCompetencias($this->tb, $this->adapter);
		$result = $model->get($filter, null, false)->toArray();
		$result = current($result);
		$this->view['result'] = $result;
		
		//echo '<pre>'; print_r($result); exit;
		
		$viewModel = new ViewModel( $this->view );
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
	public function relatorioFinalAction()
	{
		$this->view['id_login'] = $id_login = $this->params('id');
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $id_login . '"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['perfil'] = $modellogin->get($filter, null, true);
		$this->view['userdata'] = $this->view['perfil']->current();
		
		//echo '<pre>'; print_r($this->view['userdata']); exit;
		
		//select na inteligencia
		$where = "inteligencias_multiplas.id_login = '$id_login'";
		$model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->current();
		$result = $model->tresMaioresPontuacoes($result);
		$result = array_keys($result);
		$this->view['tres'] = $result;
		
		//select na aptidao
		$aptidao = new ModelAptidoesProfissionais($this->tb, $this->adapter);
		$this->view['resultaptidao'] = $aptidao->getResultadoAptidaoProfissional($id_login);
		$this->view['aptidao'] = $aptidao->tresMaioresPontuacoes($this->view['resultaptidao']);
		
		$this->view['ancora'] = array();
		foreach ($this->view['aptidao'] as $row){
			$this->view['ancora'][] = $row['ancora'];
		}
		
		//select no pontos fortes
		$where = "pontos_fortes.id_login = '$id_login'";
		$model = new ModelPontosFortes($this->tb, $this->adapter);
		$result = $model->get( ['expr'=>$where] )->toArray();
		$result = array_column($result, 'value');
		$this->view['pontos_fortes'] = $result;
		
		//select nas competencias
		$where = "competencias.id_login = '$id_login'";
		$model = new ModelCompetencias($this->tb, $this->adapter);
		$result = $model->get(['expr'=>$where])->toArray();
		$result = current($result);
		$this->view['competencias'] = $result;
		
		//echo '<pre>'; print_r($this->view['competencias']); exit;
		
		//select no perfil
		$filter = array();
		$filter['expr'] = 'perfilComportamental.id_login = "' . $id_login . '"';
		$model = new ModelPerfilComportamental($this->tb, $this->adapter);
		$this->view['result'] = $model->get($filter)->toArray();
		$this->view['resultado'] = $model->resultado( $this->view['result']);
		$this->view['perfilresult'] = (object) ($this->view['result']);
		$this->view['perfil'] = array();
		foreach ($this->view['perfilresult'] as $row){
			$this->view['perfil'][] = $row['name'];
		}
		
		//passo 1
		$result = array();
		$relatorio = new Relatorio();
		$result['im'] = $relatorio->getPontuacao($this->view['tres'], "im");
		$result['ancoras'] = $relatorio->getPontuacao($this->view['ancora'], "ancoras");
		$result['perfil'] = $relatorio->getPontuacaoPerfil($this->view['perfil']);
		$result['pontos_f'] = $relatorio->getPontuacao($this->view['pontos_fortes'], "pontos_f");
		$result['aa_comp'] = $relatorio->getPontuacaoCompetencias($this->view['competencias']);
		$cps = $relatorio->somarCP($result);
		$this->view['cps']= $relatorio->porcentagemTotalCP($cps);
		$this->view['carreiras'] = $relatorio->definirCarreiras($cps);
		$this->view['carreiras_total'] = $relatorio->porcentagemTotalCarreiras($cps);
		
		
		//criar outro array com todas as infos
		$i = 0;
		arsort($this->view['cps']);
		$this->view['chart_data'] = array();
		foreach( $this->view['cps'] as $competencia => $value )
		{
			$i++;
			$color = ($i <= 5) ? 'green' : (($i <= 10) ? 'yellow' : 'red');
			$this->view['chart_data'][$i]['order'] = (int)str_replace('cp', '', $competencia);
			$this->view['chart_data'][$i]['competencia'] = \Application\Classes\Relatorio::getCompetenciaNome($competencia);
			$this->view['chart_data'][$i]['y'] = (float)$value;
			$this->view['chart_data'][$i]['color'] = $color;
		}
		usort($this->view['chart_data'], function($a, $b){
			return $a['order'] - $b['order'];
		});
			//     	echo '<pre>'; print_r($this->view['chart_data']); exit;
			//echo '<pre>'; print_r(array_column($this->view['chart_data'], 'competencia')); exit;
			
			
			//seleciona os valores para o texto (competencias)
			$competencias = $this->view['cps'];
			arsort($competencias);
			
			$this->view['maiorescps'] = array_slice($competencias, 0, 5);
			$this->view['medioscps'] = array_slice($competencias, 5, 5);
			$this->view['menorescps'] = array_slice($competencias, 10);
			
			//Seleciona as carreiras
			$carreiras = $this->view['carreiras_total'];
			arsort($carreiras);
			
			$this->view['maiorcarreira'] = array_slice($carreiras, 0, 1);
			$this->view['maiorcarreirac'] = current(array_slice($carreiras, 0, 1));
			
			//echo '<pre>'; print_r($this->view['maiorcarreira']); exit;
			
			$viewModel = new ViewModel( $this->view );
			$viewModel->setTerminal(true);
			return $viewModel;
	}
	
	public function RelatorioAnaliseAction()
	{
	    $this->view['id_login'] = $id_login = $this->params('id');
	    
	    $filter = array();
	    $filter['expr'] = 'id_login = "' . $id_login . '"';
	    $modellogin = new ModelLogin($this->tb, $this->adapter);
	    $this->view['perfil'] = $modellogin->get($filter, null, true);
	    
	    //select na inteligencia
	    $where = "inteligencias_multiplas.id_login = '$id_login'";
	    $model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
	    $result = $model->get( ['expr'=>$where] )->current();
	    $result = $model->tresMaioresPontuacoes($result);
	    $result = array_keys($result);
	    $this->view['tres'] = $result;
	    
	    //echo '<pre>'; print_r($this->view['tres']); exit;
	    
	    $viewModel = new ViewModel( $this->view );
	    $viewModel->setTerminal(true);
	    return $viewModel;
	}
	
	public function relatorioIpAction()
	{
	    $this->init();
	    
	    //definir id do login
	    $this->view['id_login'] = $id_login = $this->params('id');
	    
	    //selecionar respostas
	    $filter = array();
	    $filter['expr'] = 'analiseEntrevista.id_login = "' . $id_login . '"';
	    $model = new ModelAnaliseEntrevista($this->tb, $this->adapter);
	    $this->view['result'] = $model->get($filter)->toArray();
	    $this->view['result'] = (object) current($this->view['result']);
	    
	    //SELECIONA user
	    $filter = array();
	    $filter['expr'] = 'id_login = "' . $id_login . '"';
	    $modellogin = new ModelLogin($this->tb, $this->adapter);
	    $this->view['usuariocr'] = $modellogin->get($filter, false, 'page_1')->current();
	    
	    
	    //echo '<pre>'; print_r( $this->view['result']); exit;
	    
	    //title
	    $this->head->setTitle('Análise de currículo');
	    
	    $viewModel = new ViewModel( $this->view );
	    return $viewModel;
	}
	
	
	protected function salvar()
	{
		
		$filter = array();
		$filter['expr'] = 'id_login = "' . $this->layout()->me->id_login. '"';
		$model = new ModelLogin($this->tb, $this->adapter);
		$this->view['user'] = $model->get($filter)->current();
		
		try {
			
			//params
			$params = $this->params()->fromPost();
			
			if (empty($params['senha_confirmar'])){
				unset($params['senha'], $params['senha_confirmar']);
			}

			if ($params['email'] == $this->view['user']->email){
				unset($params['email']);
			}
			//echo '<pre>'; print_r($params); exit;
			
			//upload
// 			$file = $_FILES['imagem'];
// 			if( !empty($file['name']) )
// 			{
// 				$path = "/assets/application/uploads/";
// 				$upload = new \Naicheframework\Upload\Upload($path);
// 				$upload->setExtensions(["jpg","png","gif"])->file($file, "user");
// 				if( !empty($upload->getError()) ) throw new \Exception("Erro no upload da imagem!");
// 				$params['imagem'] = $upload->getFilenameCurrent();
// 			}
			
			//echo '<pre>'; print_r(); exit;
			
			//validar
			\Application\Validate\ValidateUsuarioEdit::$tb = $this->tb;
			\Application\Validate\ValidateUsuarioEdit::$adapter = $this->adapter;
			$validate = \Application\Validate\ValidateUsuarioEdit::cadastroEdit($params);
			if( $validate !== true ) throw new \Exception($validate);
			
			//salva na tabela
			
			$params['id_login'] = $this->layout()->me->id_login;
			$model = new ModelLogin($this->tb, $this->adapter);
			
			//salva no banco
			$model->save($params, $params['id_login']);
			
			//redirect
			return $this->redirect()->toUrl('/minha-conta/perfil');
			
		} catch(\Exception $e)
		{
			
			$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
			$this->view['data'] = (object)$params;
			
		}
	}
	
}