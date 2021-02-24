<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelAnaliseDeCurriculo;
use Application\Model\ModelAnaliseCursos;
use Application\Model\ModelAnaliseExperienciaProfissional;
use Application\Model\ModelAnaliseExperienciaInternacional;
use Application\Model\ModelLogin;
use Application\Model\ModelDadosEscolares;
use Application\Model\ModelPosGraduacao;
use Application\Model\ModelIdiomas;

class Trilha1Controller extends GlobalController
{
	
	private $id = null;
	
	private function init()
	{
		$breadcrumb = [];
		$breadcrumb['Inicio'] = 'https://wantu.com.br';
		$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
		$breadcrumb['Currículo'] = 'javascript:;';
		$this->layout()->setVariable('breadcrumb', $breadcrumb);
		
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
 					//echo '<pre>'; print_r($this->view['data']['data_de_nascimento']); exit;
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
	
	public function salvaAction(){
		
		
		
		$post = $this->params()->fromPost();
		//$this->salvarEtapa5($post);
		
	
		//salva na tabela
		
		
		//params
		$params = $post;
		$params['id_analise_de_curriculo'] = $post['code'];
		$params['id_login'] = $this->layout()->me->id_login;
		
		// Arruma data
		$data = $params['data_de_inicio_empresarial'];
		$data = explode("/", $params['data_de_inicio_empresarial']);
		$params['data_de_inicio_empresarial'] = $data[2] . "-" . $data[1] . "-" . $data[0];
		
		$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
		
		//salva no banco
		$save = $model->setAnaliseExperienciaProdissional($params);

		//renderiza o HTML
		$viewRender = $this->getServiceLocator()->get('ViewRenderer');
		$html = $viewRender->render("/partials/analise/listagem.phtml", ['result'=>$save['data']]);
		

		echo json_encode(array('html'=>$html,'id'=>$save['data']['id_analise_experiencia_profissional'])); exit;
		//echo '<pre>'; print_r($post); exit;
	}
    
    public function indexAction()
    {
    	$this->init();
    	
    	$this->view['states'] = \Naicheframework\Helper\Constants::getStates();
    	
    	return new ViewModel($this->view);
    }
    
    public function documentosAction()
    {
    	$this->init();
    	
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	
    	return new ViewModel($this->view);
    }
    
    public function dadosEscolaresAction()
    {
    	$this->init();
    	
    	//selecionar cursos
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$modelCursos = new ModelDadosEscolares($this->tb, $this->adapter);
    	$this->view['data3'] = $modelCursos->get($filter)->toArray();
    	
    	//echo '<pre>'; print_r($data3); exit;
    	
    	//libs
    	$this->head->setJS('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', true);
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->head->setCss('/js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
    	$this->head->setJs("helpers/accordion.js");
    	
    	return new ViewModel($this->view);
    }
    
    public function posGraduacaoAction()
    {
    	$this->init();
    	
    	//selecionar cursos
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$modelCursos = new ModelPosGraduacao($this->tb, $this->adapter);
    	$this->view['data3'] = $modelCursos->get($filter)->toArray();
    	
    	//libs
    	$this->head->setJS('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', true);
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->head->setCss('../js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
    	$this->head->setJs("helpers/accordion.js");
    	
    	return new ViewModel($this->view);
    }
    
    public function cursosAction()
    {
    	$this->init();
    	
    	//echo '<pre>'; print_r($this->id); exit;
    	
    	//selecionar cursos
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$modelCursos = new ModelAnaliseCursos($this->tb, $this->adapter);
    	$this->view['data'] = $modelCursos->get($filter)->toArray();
    	
    	//echo '<pre>'; print_r($this->view['data']); exit;
    	
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->head->setCss('/assets/application/js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
    	
    	return new ViewModel($this->view);
    }
  
    public function experienciaProfissionalAction()
    {
    	$this->init();
    	
    	//selecionar experiencia profissional
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
    	$this->view['data2'] = $model->get($filter)->toArray();
    	
    	
//     	//selecionar analise
//     	$filter = array();
//     	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
//     	$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
//     	$this->view['data_experi'] = $model->get($filter)->toArray();
    	
    	//echo '<pre>'; print_r($this->view['data_experi']); exit;
    	
    	$this->head->setJs("helpers/accordion.js");
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->head->setCss('../js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
    	
    	return new ViewModel($this->view);
    }
    
    public function experienciaInternacionalAction()
    {
    	$this->init();
    	
    	//selecionar experiencia internacional 
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$model = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
    	$this->view['data'] = $model->get($filter)->toArray();
    	
    	//echo '<pre>'; print_r($this->view['data']); exit;
    	
    	$this->head->setJs("helpers/accordion.js");
    	
    	
    	return new ViewModel($this->view);
    }
    
    public function idiomasAction()
    {
    	$this->init();
    	
    	//die('lala');
    	
    	//selecionar experiencia internacional
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$model = new ModelIdiomas($this->tb, $this->adapter);
    	$this->view['data'] = $model->get($filter)->toArray();
    	
    	//echo '<pre>'; print_r($this->view['data']); exit;
    	
    	$this->head->setJs("helpers/accordion.js");
    	$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->head->setCss('../js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
    	
    	
    	return new ViewModel($this->view);
    }
    
    public function informacoesComplementaresAction()
    {
        
        $this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
    	$this->init();
    	
    	return new ViewModel($this->view);
    }
    
    public function entrevistaAction()
    {
        $this->init();
         
        return new ViewModel($this->view);
    }
    
    protected function salvarEtapa1()
    {
    	
    	try {
    		
    		//params
    		$params = $this->params()->fromPost();
    		
    		//echo '<pre>'; print_r($params = $this->params()->fromPost()); exit;
    		
//     		//validar
    		$validate = \Application\Validate\ValidateAnalise::etapa1($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
	    	//salva na tabela
	    	
    		$date = implode('-', array_reverse(explode('/', $params['data_de_nascimento'])));
    		$params['data_de_nascimento'] = $date;
    		
	    	$params['id_login'] = $this->layout()->me->id_login;
	    	$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
	    	
	    	//salva no banco
	    	$params['id_analise_de_curriculo'] = $model->save($params, $params['code']);
	    	
	    	//redirect
	    	return $this->redirect()->toUrl('/trilha1/documentos');
    	
    	} catch(\Exception $e)
    	{
    		
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    		
    	}
    }
    
    protected function salvarEtapa2()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		
//     		//validar
    		$validate = \Application\Validate\ValidateDocumentosAnalise::etapa2($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
    		
    		//salva no banco
    		$params['id_analise_de_curriculo'] = $model->save($params, $params['code']);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/dados-escolares');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function salvarEtapa3()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		$params['id_analise_de_curriculo'] = $this->id;
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelDadosEscolares($this->tb, $this->adapter);
    		
    		//salva no banco
    		$model->save($params);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/dados-escolares');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    		
    }
    
    protected function salvarEtapa4()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		
    		// Arruma data
    		$data = $params['conclusao_curso'];
    		$data = explode("/", $params['conclusao_curso']);
    		$params['conclusao_curso'] = $data[2] . "-" . $data[1] . "-" . $data[0];
    		
    		$params['id_analise_de_curriculo'] = $this->id;
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelAnaliseCursos($this->tb, $this->adapter);
    		
    		//salva no banco
    		$model->save($params);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/cursos');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    protected function salvarEtapa6()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		$params['id_analise_de_curriculo'] = $this->id;
    		$params['id_login'] = $this->layout()->me->id_login;
    		
    		//echo '<pre>'; print_r($params); exit;
    		
    		$model = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
    		
    		//salva no banco
    		$params['id_analise_de_curriculo'] = $model->save($params, $params['code']);
    		
    		//redirect.
    		return $this->redirect()->toUrl('/trilha1/experiencia-internacional');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function salvarEtapaIdioma()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		$params['id_analise_de_curriculo'] = $this->id;
    		$params['id_login'] = $this->layout()->me->id_login;
    		
    		//echo '<pre>'; print_r($params); exit;
    		
    		$model = new ModelIdiomas($this->tb, $this->adapter);
    		
    		//salva no banco
    		$model->save($params, $params['code']);
    		
    		//redirect.
    		return $this->redirect()->toUrl('/trilha1/idiomas');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    
    protected function salvarEtapa7()
    {
    	//echo '<pre>'; print_r($_POST); exit;
    	try
    	{
    		
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		
    		//salvar na tabela
    		$params['analise_de_curriculo'] = "Completo";
    		$modellogin = new ModelLogin($this->tb, $this->adapter);
    		$modellogin->save($params, $this->layout()->me->id_login);
    		
    		//validar
    		$validate = \Application\Validate\ValidateInformacoesComplementaresAnalise::etapa7($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
    		//echo '<pre>'; print_r($params); exit;
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
    		
    		//echo '<pre>'; print_r($params); exit;
    		
    		//salva no banco
    		$params['id_analise_de_curriculo'] = $model->save($params, $params['code']);
    		
    		//redirect
    		if ( $params['button'] == 'download' ){
    			
    			return $this->redirect()->toUrl('/trilha1/curriculo');
    			
    		} else {
    			
    			return $this->redirect()->toUrl('/trilha1/entrevista');
    		}
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    protected function salvarEtapanew()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		
    		//validar
    		$validate = \Application\Validate\ValidateExperienciaProfissionalAnalise::etapanew($params);
    		if( $validate !== true ) throw new \Exception($validate);
    		
    		
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
    		
    		//echo '<pre>'; print_r($params); exit;
    		
    		//salva no banco
    		$params['id_analise_de_curriculo'] = $model->save($params, $this->id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/experiencia-internacional');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function salvarEtapaPos()
    {
    	try
    	{
    		//salva na tabela
    		//params
    		$params = $this->params()->fromPost();
    		$params['id_analise_de_curriculo'] = $this->id;
    		$params['id_login'] = $this->layout()->me->id_login;
    		$model = new ModelPosGraduacao($this->tb, $this->adapter);
    		
    		//echo '<pre>'; print_r($params['id_analise_de_curriculo']); exit;
    		
    		//salva no banco
    		$model->save($params);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/pos-graduacao');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    	
    }
    
    
    public function progressoAction()
    {
    	
    	return new ViewModel($this->view);
    }
    
    public function curriculoAction()
    {
        
    	$this->init();
    	
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

    	//echo '<pre>'; print_r($this->view['info_complementares']); exit;
    	
    	//selecionar experiencia profissional
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['order'] = 'data_de_inicio_empresarial DESC';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
    	$this->view['profissional'] = $model->get($filter)->toArray();
    	
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
    	
    	//selecionar Pos
    	$filter = array();
    	$filter['expr'] = 'id_analise_de_curriculo = "'. $this->id.'"';
    	$filter['expr'] .= ' AND status != "Excluido"';
    	$modelCursos = new ModelIdiomas($this->tb, $this->adapter);
    	$this->view['dados_idiomas'] = $modelCursos->get($filter)->toArray();
    	
    	return new ViewModel($this->view);
    }
    
    protected function deletarCursoAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelAnaliseCursos($this->tb, $this->adapter);
    		$params['id_analise_cursos'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/cursos');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarExperienciaProfissionalAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelAnaliseExperienciaProfissional($this->tb, $this->adapter);
    		$params['id_analise_experiencia_profissional'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/experiencia-profissional');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarExperienciaInternacionalAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelAnaliseExperienciaInternacional($this->tb, $this->adapter);
    		$params['id_analise_experiencia_internacional'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/experiencia-internacional');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarPosAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelPosGraduacao($this->tb, $this->adapter);
    		$params['id_pos_graduacao'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/pos-graduacao');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarDadosEscolaresAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelDadosEscolares($this->tb, $this->adapter);
    		$params['id_dados_escolares'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/dados-escolares');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function deletarIdiomasAction()
    {
    	try
    	{
    		//id
    		$id = $this->params('id');
    		
    		//salva na tabela
    		$params = $this->params()->fromPost();
    		$params['status'] = "Excluido";
    		
    		//salva no banco
    		$model = new ModelIdiomas($this->tb, $this->adapter);
    		$params['id_dados_escolares'] = $model->save($params, $id);
    		
    		//redirect
    		return $this->redirect()->toUrl('/trilha1/idiomas');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
}

