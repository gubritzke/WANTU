<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Application\Model\ModelLogin;
use Application\Model\ModelInteligenciasMultiplas;
use Application\Model\ModelAptidoesProfissionais;
use Application\Model\ModelPontosFortes;
use Application\Model\ModelCompetencias;
use Application\Model\ModelPerfilComportamental;
use Application\Classes\Relatorio;
use Application\Model\ModelResultadosFinais;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Classes\MailMessage;
use Application\Model\ModelResultadosFinaisCompleto;

class RotinaTestesController extends GlobalController
{
    protected function init()
    {
        //validar token
        $token = $this->params()->fromQuery('token');
        if( $token != 'N@!CH3' ) die('Acesso restrito!');
    }
    
    /**
     * /rotina-testes/rotina-teste?token=N@!CH3
     * atualiza o status do pagamento consultando na API do Gateway
     * executado de 30 em 30 minutos
     */
    public function RotinaTesteAction()
    {
        $this->init();
        
        $filter = array();
        $filter['expr'] = 'perfil_comportamental = "Completo"';
        $filter['expr'] .= ' AND aptidoes_profissionais = "Completo"';
        $filter['expr'] .= ' AND inteligencias_multiplas = "Completo"';
        $filter['expr'] .= ' AND pontos_fortes = "Completo"';
        $filter['expr'] .= ' AND competencias = "Completo"';
        $filter['expr'] .= ' AND id_resultados_finais IS NULL';
        $modellogin = new ModelLogin($this->tb, $this->adapter);
        $this->view['perfil'] = $modellogin->get3($filter, null, true);
        $this->view['userdata'] = $this->view['perfil'];
        
        //echo'<pre>'; print_r($this->view['userdata']->toArray()); exit;
        
        $user = [];
        
        foreach ( $this->view['userdata'] as $key => $row ){
            
            $id_login = $row['id_login'];
            
            $user[$key]['user'] = $row;
            
            //select na inteligencia
            $where = "inteligencias_multiplas.id_login = '$id_login'";
            $model = new ModelInteligenciasMultiplas($this->tb, $this->adapter);
            $result = $model->get( ['expr'=>$where] )->current();
            $result = $model->tresMaioresPontuacoes($result);
            $result = array_keys($result);
            $tres = $result;
            
            //select na aptidao
            $aptidao = new ModelAptidoesProfissionais($this->tb, $this->adapter);
            $resultaptidao = $aptidao->getResultadoAptidaoProfissional($id_login);
            $resuaptidao = $aptidao->tresMaioresPontuacoes($resultaptidao);
            
            $ancora = array();
            foreach ($resuaptidao as $row){
                $ancora[] = $row['ancora'];
            }
            
            //select no pontos fortes
            $where = "pontos_fortes.id_login = '$id_login'";
            $model = new ModelPontosFortes($this->tb, $this->adapter);
            $result = $model->get( ['expr'=>$where] )->toArray();
            $result = array_column($result, 'value');
            $pontos_fortes = $result;
            
            //select nas competencias
            $where = "competencias.id_login = '$id_login'";
            $model = new ModelCompetencias($this->tb, $this->adapter);
            $result = $model->get(['expr'=>$where])->toArray();
            $result = current($result);
            $competencias = $result;
            
            //echo '<pre>'; print_r($this->view['competencias']); exit;
            
            //select no perfil
            $filter = array();
            $filter['expr'] = 'perfilComportamental.id_login = "' . $id_login . '"';
            $model = new ModelPerfilComportamental($this->tb, $this->adapter);
            $resultperfil1 = $model->get($filter)->toArray();
            $resultado1 = $model->resultado( $resultperfil1);
            $perfilresult = (object) ($resultperfil1);
            $perfil1 = array();
            foreach ($perfilresult as $row){
                $perfil1[] = $row['name'];
            }
            
            //passo 1
            $result = array();
            $relatorio = new Relatorio();
            $result['im'] = $relatorio->getPontuacao($tres, "im");
            $result['ancoras'] = $relatorio->getPontuacao($ancora, "ancoras");
            $result['perfil'] = $relatorio->getPontuacaoPerfil($perfil1);
            $result['pontos_f'] = $relatorio->getPontuacao($pontos_fortes, "pontos_f");
            $result['aa_comp'] = $relatorio->getPontuacaoCompetencias($competencias);
            $cps = $relatorio->somarCP($result);
            $cpscount = $relatorio->porcentagemTotalCP($cps);
            $carreiras = $relatorio->definirCarreiras($cps);
            $user[$key]['carreiras_total'] = $relatorio->porcentagemTotalCarreiras($cps);
            
            
            //criar outro array com todas as infos
            $i = 0;
            arsort($cpscount);
            $chart_data = array();
            foreach($cpscount as $competencia => $value )
            {
                $i++;
                $color = ($i <= 5) ? 'green' : (($i <= 10) ? 'yellow' : 'red');
                $chart_data[$i]['order'] = (int)str_replace('cp', '', $competencia);
                $chart_data[$i]['competencia'] = \Application\Classes\Relatorio::getCompetenciaNome($competencia);
                $chart_data[$i]['y'] = (float)$value;
                $chart_data[$i]['color'] = $color;
            }
            usort($chart_data, function($a, $b){
                return $a['order'] - $b['order'];
            });
                //     	echo '<pre>'; print_r($this->view['chart_data']); exit;
                //echo '<pre>'; print_r(array_column($this->view['chart_data'], 'competencia')); exit;
                
                $user[$key]['todoscps'] = $cpscount;
                
                //echo '<pre>'; print_r($user[$key]['todoscps']); exit;    
            
                //seleciona os valores para o texto (competencias)
                $competencias = $cpscount;
                arsort($competencias);
                
                $user[$key]['maiorescps'] = array_slice($competencias, 0, 5);
                $medioscps = array_slice($competencias, 5, 5);
                $menorescps = array_slice($competencias, 10);
                
                
                //Seleciona as carreiras
                $carreiras = $user[$key]['carreiras_total'];
                arsort($carreiras);
                
                $user[$key]['maior_carreira'] = array_slice($carreiras, 0, 1);
                $maiorcarreirac = current(array_slice($carreiras, 0, 1));
                
                //echo '<pre>'; print_r($user[$key]['maior_carreira']); exit;
                
        }
        
        
        foreach( $user as $row )
        {
            //echo'<pre>'; print_r($row); exit;   
            
            //salva no banco
            $model = new ModelResultadosFinais($this->tb, $this->adapter);
            
            $params = array();
            $params['id_login'] =  $row['user']->id_login;
            $params['maior_carreira'] = key($row['maior_carreira']);
            $params['academica'] = $row['carreiras_total'][academica];
            $params['empreendedora'] = $row['carreiras_total'][empreendedora];
            $params['gerencial'] = $row['carreiras_total'][gerencial];
            $params['publica'] = $row['carreiras_total'][publica];
            $params['politica'] = $row['carreiras_total'][politica];
            $params['especialista'] = $row['carreiras_total'][especialista];
            $params['cp1'] = $row['maiorescps'][cp1];
            $params['cp2'] = $row['maiorescps'][cp2];
            $params['cp3'] = $row['maiorescps'][cp3];
            $params['cp4'] = $row['maiorescps'][cp4];
            $params['cp5'] = $row['maiorescps'][cp5];
            $params['cp6'] = $row['maiorescps'][cp6];
            $params['cp7'] = $row['maiorescps'][cp7];
            $params['cp8'] = $row['maiorescps'][cp8];
            $params['cp9'] = $row['maiorescps'][cp9];
            $params['cp10'] = $row['maiorescps'][cp10];
            $params['cp11'] = $row['maiorescps'][cp11];
            $params['cp12'] = $row['maiorescps'][cp12];
            $params['cp13'] = $row['maiorescps'][cp13];
            $params['cp14'] = $row['maiorescps'][cp14];
            $params['cp15'] = $row['maiorescps'][cp15];
            
            //echo'<pre>'; print_r($params); exit;   
            $model->save($params);
            
            //enviar email notificando
            $replace = array('nome' => $row->nome);
            $mail = new MailMessage();
            $mail->testesConcluidos('gustavo.britzke@naiche.com.br', $replace);
            
            //echo '<pre>'; print_r( $mail ); exit;
            
        }
        
        foreach( $user as $row )
        {
            //echo'<pre>'; print_r($row); exit;
            
            //salva no banco
            $model = new ModelResultadosFinaisCompleto($this->tb, $this->adapter);
            
            $params = array();
            $params['id_login'] =  $row['user']->id_login;
            $params['maior_carreira'] = key($row['maior_carreira']);
            $params['academica'] = $row['carreiras_total'][academica];
            $params['empreendedora'] = $row['carreiras_total'][empreendedora];
            $params['gerencial'] = $row['carreiras_total'][gerencial];
            $params['publica'] = $row['carreiras_total'][publica];
            $params['politica'] = $row['carreiras_total'][politica];
            $params['especialista'] = $row['carreiras_total'][especialista];
            $params['cp1'] = $row['todoscps'][cp1];
            $params['cp2'] = $row['todoscps'][cp2];
            $params['cp3'] = $row['todoscps'][cp3];
            $params['cp4'] = $row['todoscps'][cp4];
            $params['cp5'] = $row['todoscps'][cp5];
            $params['cp6'] = $row['todoscps'][cp6];
            $params['cp7'] = $row['todoscps'][cp7];
            $params['cp8'] = $row['todoscps'][cp8];
            $params['cp9'] = $row['todoscps'][cp9];
            $params['cp10'] = $row['todoscps'][cp10];
            $params['cp11'] = $row['todoscps'][cp11];
            $params['cp12'] = $row['todoscps'][cp12];
            $params['cp13'] = $row['todoscps'][cp13];
            $params['cp14'] = $row['todoscps'][cp14];
            $params['cp15'] = $row['todoscps'][cp15];
            
            //echo'<pre>'; print_r($params); exit;
            $model->save($params);
            
            
        }
            
        //echo '<pre>'; print_r( $user ); exit;
        
        die('Concluído com sucesso! =)');
    }
}