<?php
namespace Painel\Controller;
use Zend\View\Model\ViewModel;
use Application\Model\ModelAnaliseDeCurriculo;
use Application\Model\ModelAnaliseEntrevista;
use Application\Model\ModelPerfilComportamental;
use Application\Model\ModelLogin;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelInteligenciasMultiplas;
use Application\Model\ModelPontosFortes;
use Application\Model\ModelCompetencias;

class RelatoriosGeraisController extends \Application\Classes\GlobalController
{
	private function init()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
	}
	
	public function indexAction()
    {
    	$this->init();
		
        return new ViewModel($this->view);
    }
    
    public function AnaliseDeCurriculoAction()
    {
        $this->init();
        
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND analise_de_curriculo = "Completo"';
        
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
        
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['curriculo'] = $modellogin->get3(["expr"=>$where], true, 'page_6', 20);
    
        //title
        $this->head->setTitle('Análise de currículo');
        $this->head->addCalendar();
        $this->head->addMask();
        
        
        return new ViewModel($this->view);
    }
    
    public function PerfilComportamentalAction()
    {
        $this->init();
    
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND perfil_comportamental = "Completo"';
    
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
    
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['perfil'] = $modellogin->get3(["expr"=>$where], true, 'page_1', 20);
    
        //title
        $this->head->setTitle('Perfil Comportamental');
        $this->head->addCalendar();
        $this->head->addMask();
        
    
        return new ViewModel($this->view);
    }
   
    public function AptidoesProfissionaisAction()
    {
        $this->init();
    
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND aptidoes_profissionais = "Completo"';
    
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
    
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['aptidao'] = $modellogin->get3(["expr"=>$where], true, 'page_2', 20);
    
        //title
        $this->head->setTitle('Perfil Comportamental');
        $this->head->addCalendar();
        $this->head->addMask();
        
    
        return new ViewModel($this->view);
    }
    
    public function InteligenciasMultiplasAction()
    {
        $this->init();
    
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND inteligencias_multiplas = "Completo"';
    
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
    
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['inteligencia'] = $modellogin->get3(["expr"=>$where], true, 'page_3', 20);
    
        //title
        $this->head->setTitle('Perfil Comportamental');
        $this->head->addCalendar();
        $this->head->addMask();
        
    
        return new ViewModel($this->view);
    }
     
    public function PontosForteAction()
    {
        $this->init();
    
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND pontos_fortes = "Completo"';
    
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
    
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['pontos_fortes'] = $modellogin->get3(["expr"=>$where], true, 'page_4', 20);
    
        //title
        $this->head->setTitle('Perfil Comportamental');
        $this->head->addCalendar();
        $this->head->addMask();
        
    
        return new ViewModel($this->view);
    }
     
    public function CompetenciasAction()
    {
        $this->init();
    
        $this->view['filter'] = $this->params()->fromQuery();
        $where = " (login.nivel = 0) ";
        $where .= ' AND competencias = "Completo"';
    
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
    
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['competencias'] = $modellogin->get3(["expr"=>$where], true, 'page_5', 20);
    
        //title
        $this->head->setTitle('Perfil Comportamental');
        $this->head->addCalendar();
        $this->head->addMask();
        
    
        return new ViewModel($this->view);
    }
    
    public function analiseAction()
    {
    	$this->init();
    	
    	//title
    	$this->head->setTitle('Análise de currículo');
    	
    	//definir id do login
    	$this->view['id_login'] = $id_login = $this->params('id');
    	
    	//selecionar respostas
    	$filter = array();
    	$filter['expr'] = 'analiseEntrevista.id_login = "' . $id_login . '"';
    	$model = new ModelAnaliseEntrevista($this->tb, $this->adapter);
    	$this->view['result'] = $model->get($filter)->toArray();
    	$this->view['result'] = (object) current($this->view['result']);
    	
    	//echo'<pre>'; print_r($this->view['result']); exit;

    	$this->head->setJs("helpers/accordion.js");
    	return new ViewModel($this->view);
    }
    public function relatorioAnaliseAction()
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
    
    public function relatorioPerfilAction()
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
    
    public function relatorioAptidaoAction()
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
    
    public function inteligenciaMultiplasAction()
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
    
    public function pontosFortesAction()
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
    
    public function relatorioCompetenciasAction()
    {
    	
    	//definir id do login
    	$this->view['id_login'] = $id_login = $this->params('id');
    	
    	//echo '<pre>'; print_r($this->view['id_login']); exit;
    	
    	$filter = array();
    	$filter['expr'] = 'pontos_fortes = "Completo"';
    	$filter['expr'] = 'login.id_login = "' . $id_login . '"';
    	$modellogin = new ModelLogin($this->tb, $this->adapter);
    	$this->view['perfil'] = $modellogin->get($filter, null, true);
    	
    	//selecionar respostas
    	$where = "competencias.id_login = '$id_login'";
    	$model = new ModelCompetencias($this->tb, $this->adapter);
    	$result = $model->get($filter, null, false)->toArray();
    	$result = current($result);
    	$this->view['result'] = $result;
    	
     	//echo '<pre>'; print_r($result); exit;
    	
    	$viewModel = new ViewModel( $this->view );
    	$viewModel->setTerminal(true);
    	return $viewModel;
    }
    
    protected function salvar()
    {
    	try
    	{
    		//params
    		$id_login = $this->params('id');
    		$params = $this->params()->fromPost();
    		$params['id_login'] = $id_login;
    		
    		//contas
    		//calcular onde estou
    		$calc = array_sum([$params['p1'], $params['p2'], $params['p3'], $params['p4'], $params['p5'], $params['p6'], $params['p7'], $params['p8'], $params['p9']]);
    		$calc = $calc / 6 * 100;
    		$params['total_onde_estou'] = number_format($calc, 2);
    		
    		//calcular para onde vou
    		$calc = array_sum([$params['p10'], $params['p11'], $params['p12'], $params['p13'],$params['p14'], $params['p15'], $params['p16'], $params['p17'], $params['p18'], $params['p19'], $params['p20'], $params['p21'], $params['p22'], $params['p23'], $params['p24']]);
    		$calc = $calc / 10 * 100;
    		$params['total_para_onde_vou']= number_format($calc, 2);
    		
    		//como faco para chegar la
    		$calc = array_sum([$params['p25'], $params['p26'], $params['p27'], $params['p28'], $params['p29'], $params['p30'], $params['p31'], $params['p32'], $params['p33'], $params['p34'], $params['p35'], $params['p36'], $params['p37'],$params['p38'], $params['p39'], $params['p40'], $params['p41'], $params['42'], $params['p43'], $params['p44'], $params['p45'], $params['p46'], $params['p47'], $params['p48'], $params['p49'], $params['p50'], $params['p51'], $params['p52'], $params['p53'], $params['p54']]);
    		$calc = $calc / 20 * 100;
    		$params['total_como_faco_para_chegar'] = number_format($calc, 2);
    		
    		//total das contas
    		
    		$params['total_indice_de_perdidice'] = (0.2 * $params['total_onde_estou'])+(0.3 * $params['total_para_onde_vou'])+(0.5 * $params['total_como_faco_para_chegar']);
    		
			//echo '<pre>'; print_r($params); exit;
    		
    		//salva no banco
    		$model = new ModelAnaliseEntrevista($this->tb, $this->adapter);
    		$model->save($params, $params['id_analise_entrevista']);
    		
    		//redirect
    		return $this->redirect()->toUrl('/painel/relatorios-gerais/analise/' . $id_login . '');
    		
    	} catch(\Exception $e)
    	{
    		$this->layout()->setVariable('message', array('alert' => $e->getMessage()));
    		$this->view['data'] = (object)$params;
    	}
    }
    
    protected function aguardando()
    {
    	try
    	{
    		//id
    		$id = $this->params()->fromPost("id_analise_de_curriculo");
    		
    		//destaque
    		$aguardando= $this->params()->fromPost("aguardando");
    		$set['aguardando'] = ($aguardando== 'true') ? 1 : 0;
    		
    		//salvar
    		$where= "id_analise_de_curriculo = '" . $id. "'";
    		$model = new ModelAnaliseDeCurriculo($this->tb, $this->adapter);
    		$model->update($set, $where);
    		
    		//mensagem sucesso
    		$result = array(
    			"message" => "Salvo com sucesso!"
    		);
    		
    	} catch( \Exception $e )
    	{
    		//mensagem erro
    		$result = array(
    			"error" => $e->getMessage()
    		);
    	}
    	
    	echo json_encode($result); exit;
    }
    
    
    
}