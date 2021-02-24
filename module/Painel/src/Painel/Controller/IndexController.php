<?php
namespace Painel\Controller;

use Zend\View\Model\ViewModel;
use Application\Classes\GlobalController;
use Application\Model\ModelLogin;
use Zend\Db\Sql\Where;

class IndexController extends GlobalController
{
    public function indexAction()
    { 
    	$this->head->setJs("helpers/accordion.js");
    	
    	// seleciona todos os usuarios
    	$where = " (login.nivel = 0) ";
    	$model = new ModelLogin($this->tb, $this->adapter);
    	$this->view['users'] = $model->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuarios'] = count($this->view['users']);
    	
    	//seleciona todos os usuarios masc
    	$where = " (login.nivel = 0) ";
    	$where = " (analiseDeCurriculo.sexo = 'Masculino') ";
    	$modelmasc = new ModelLogin($this->tb, $this->adapter);
    	$this->view['usersm'] = $modelmasc->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosm'] = count($this->view['usersm']);
    	
    	//seleciona todos os usuarios nao informado
    	$where = " (login.nivel = 0) ";
    	$where = " (analiseDeCurriculo.sexo = 'Não informar') ";
    	$modelfem = new ModelLogin($this->tb, $this->adapter);
    	$this->view['usersnif'] = $modelfem->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosnif'] = count($this->view['usersnif']);
    	
    	//seleciona todos os usuarios fem
    	$where = " (login.nivel = 0) ";
    	$where = " (analiseDeCurriculo.sexo = 'Feminino') ";
    	$modelfem = new ModelLogin($this->tb, $this->adapter);
    	$this->view['usersf'] = $modelfem->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosf'] = count($this->view['usersf']);
    	
    	//seleciona todos os usuarios que concluiram todos os testes
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND perfil_comportamental = "Completo"';
    	$where .= ' AND aptidoes_profissionais = "Completo"';
    	$where .= ' AND inteligencias_multiplas = "Completo"';
    	$where .= ' AND pontos_fortes = "Completo"';
    	$where .= ' AND competencias = "Completo"';
    	$modelconcluidos = new ModelLogin($this->tb, $this->adapter);
    	$this->view['userconcluidos'] = $modelconcluidos->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosconcluidos'] = count($this->view['userconcluidos']);
    	
    	//seleciona todos os usuarios que não concluiram todos os testes
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND ( ';
    	$where .= ' perfil_comportamental = "Completo"';
    	$where .= ' OR aptidoes_profissionais = "Completo"';
    	$where .= ' OR inteligencias_multiplas  = "Completo"';
    	$where .= ' OR pontos_fortes = "Completo"';
    	$where .= ' OR competencias = "Completo"';
    	$where .= ' ) ';
    	$modelincompleto = new ModelLogin($this->tb, $this->adapter);
    	$this->view['userincompleto'] = $modelincompleto->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosincompletos'] = count($this->view['userincompleto']);
    	
    	//seleciona todos os usuarios que não realizaram nenhum teste
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND perfil_comportamental IS NULL';
    	$where .= ' AND aptidoes_profissionais IS NULL';
    	$where .= ' AND inteligencias_multiplas IS NULL';
    	$where .= ' AND pontos_fortes IS NULL';
    	$where .= ' AND competencias IS NULL';
    	//echo $where; exit;
    	$modelnfeito = new ModelLogin($this->tb, $this->adapter);
    	$this->view['usernfeito'] = $modelnfeito->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['usuariosnfeito'] = count($this->view['usernfeito']);
    	
    	/* TESTES COMPLETOS */
    	
    	//seleciona user trilha1
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND analise_de_curriculo = "Completo"';
    	$modeltrilha1 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha1'] = $modeltrilha1->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha1exibe'] = count($this->view['trilha1']);
    	
    	//seleciona user trilha2
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND perfil_comportamental = "Completo"';
    	$modeltrilha2 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha2'] = $modeltrilha2->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha2exibe'] = count($this->view['trilha2']);

    	//seleciona user trilha3
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND aptidoes_profissionais = "Completo"';
    	$modeltrilha3 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha3'] = $modeltrilha3->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha3exibe'] = count($this->view['trilha3']);

    	//seleciona user trilha4
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND inteligencias_multiplas	 = "Completo"';
    	$modeltrilha4 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha4'] = $modeltrilha4->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha4exibe'] = count($this->view['trilha4']);

    	//seleciona user trilha5
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND pontos_fortes	 = "Completo"';
    	$modeltrilha5 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha5'] = $modeltrilha5->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha5exibe'] = count($this->view['trilha5']);
    	
    	//seleciona user trilha6
    	$where = " (login.nivel = 0) ";
    	$where .= ' AND competencias	 = "Completo"';
    	$modeltrilha6 = new ModelLogin($this->tb, $this->adapter);
    	$this->view['trilha6'] = $modeltrilha6->get3(["expr"=>$where], false, true, 25)->toArray();
    	$this->view['trilha6exibe'] = count($this->view['trilha6']);
    	
    	
    	
//    	echo '<pre>'; print_r($this->view['trilha3']); exit;
    	
    	$this->head->setTitle(Painel);
    	
        return new ViewModel($this->view);
    }
    
}
