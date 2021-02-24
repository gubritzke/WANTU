<?php
namespace Painel\Controller;
use Zend\View\Model\ViewModel;
use Application\Model\ModelAnaliseDeCurriculo;
use Application\Model\ModelPerfilComportamental;
use Application\Model\ModelLogin;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelInteligenciasMultiplas;
use Application\Model\ModelPontosFortes;
use Application\Model\ModelCompetencias;
use Application\Classes\Relatorio;

class RelatorioFinalController extends \Application\Classes\GlobalController
{
	private function init()
	{
		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		//SELECIONA final
		$filter = array();
		$filter['expr'] = 'perfil_comportamental = "Completo"';
		$filter['expr'] .= ' AND aptidoes_profissionais = "Completo"';
		$filter['expr'] .= ' AND inteligencias_multiplas = "Completo"';
		$filter['expr'] .= ' AND pontos_fortes = "Completo"';
		$filter['expr'] .= ' AND competencias = "Completo"';
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$this->view['result'] = $modellogin->get($filter, true, 'page');
		
 		//echo '<pre>'; print_r($this->view['result']); exit;
		
		//title
		$this->head->setTitle('Relatorios Gerais');
		
	}
	
	public function indexAction()
    {
    	
    	$this->view['filter'] = $this->params()->fromQuery();
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND perfil_comportamental = "Completo"';
    	$where .= ' AND aptidoes_profissionais = "Completo"';
    	$where .= ' AND inteligencias_multiplas = "Completo"';
    	$where .= ' AND pontos_fortes = "Completo"';
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
    	$this->view['result'] = $modellogin->get3(["expr"=>$where], true, 'page_2', 15);		
    	return new ViewModel($this->view);
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
    	
    	//echo '<pre>'; print_r($this->view['medioscps']); exit;
    	
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
   
}