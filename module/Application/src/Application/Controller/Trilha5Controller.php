<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Zend\View\Model\ViewModel;
use Application\Model\ModelLogin;
use Application\Model\ModelPerfilComportamental;
use Application\Model\ModelPontosFortes;

class Trilha5Controller extends GlobalController
{
	
	private function init()
	{
		$this->head->addCarousel();
		$this->head->setJs('/assets/application/js/extensions/jquery.tooltipster/dist/js/tooltipster.bundle.min.js', true);
		$this->head->setCss('../js/extensions/jquery.tooltipster/dist/css/tooltipster.bundle.min.css');
		
		
		$user = new ModelLogin($this->tb, $this->adapter);
		
		$filter = [];
		$filter['where'] = 'id_login = "'.$this->layout()->me->id_login.'"';
		$get = $user->get($filter)->toArray()[0];

		if ( $get['pontos_fortes'] == 'Completo' ) {
			
			return $this->redirect()->toUrl('/trilha5/sucesso');
			
		}

		//call actions
		$method = $this->params()->fromPost('method');
		if( method_exists($this, $method) ) $this->$method();
		
		$this->view['perguntas'] = array(
			array(
				"pergunta" => "“O único tempo que tenho é o agora. Portanto, vivo um dia de cada vez, e vou me adaptando conforme a necessidade.”",
				"data" => "ADAPTABILIDADE",
				"imagem" => "1.png"
			),
			array(
				"pergunta" => "“Gosto de analisar tudo nos mínimos detalhes, procurando identificar o que está por trás dos dados. Prefiro um perfeito do que muitos feitos.”",
				"data" => "ANALÍTICO",
				"imagem" => "2.png",
			),
			array(
					"pergunta" => "“Tenho opinião firme, conheço bem minhas convicções e as utilizo para tomar minhas decisões.”",
					"data" => "AUTO-AFIRMAÇÃO",
					"imagem" => "3.png",
			),
			array(
					"pergunta" => "“Tenho um ótimo radar para fazer novos amigos, navego com facilidade entre pessoas desconhecidas, pois sou extrovertido.”",
					"data" => "CARISMA",
					"imagem" => "4.png",
			),
			array(
					"pergunta" => "“Gosto de fazer as coisas acontecer. Quando uma ideia vem, uma nova ação já vai, não importam os riscos, o que vale são as emoções.”",
					"data" => "ATIVAÇÃO",
					"imagem" => "5.png",
			),
			array(
					"pergunta" => "“Liderança está na minha veia. Não disfarço minhas grandes ambições e desejo de influenciar pessoas e comandar organizações.”",
					"data" => "COMANDO",
					"imagem" => "6.png",
			),
			array(
					"pergunta" => "“Para mim, o importante não é competir, é ganhar. Ser o primeiro, superar a mim mesmo, me destacar em tudo que faço – nos esportes, no trabalho, na vida.”",
					"data" => "COMPETIÇÃO",
					"imagem" => "7.png",
			),
			array(
					"pergunta" => "“Falar, contar histórias, bater papo. Minha vida é um livro aberto, e eu gosto de compartilhar o que sei, me expressando muito bem com as palavras certas.”",
					"data" => "COMUNICAÇÃO",
					"imagem" => "8.png",
			),
			array(
					"pergunta" => "“Ninguém está neste mundo por acaso. Acredito num plano espiritual que conecta pessoas, lugares e propósitos.”",
					"data" => "CONEXÃO",
					"imagem" => "9.png",
			),
			array(
					"pergunta" => "“Entendo o presente estudando o passado. A história me fascina com sua capacidade de nos explicar como chegamos até aqui e como podemos ir mais longe.”",
					"data" => "CONTEXTO",
					"imagem" => "10.png",
			),
			array(
					"pergunta" => "“Meus valores guiam minha vida. Gosto de me dedicar a atividades que tenham coerência entre a missão que almejo para mim, minha família, e para a sociedade.”",
					"data" => "CRENÇA",
					"imagem" => "11.png",
			),
			array(
					"pergunta" => "“Tenho um bom faro para identificar e treinar talentos. Acredito que ao preparar outras pessoas para crescer, também estou crescendo.”",
					"data" => "DESENVOLVIMENTO",
					"imagem" => "12.png",
			),
			array(
					"pergunta" => "“Metódico é uma palavra que me define. Gosto de tudo com rotinas programadas, faço com exatidão e cumpro prazos à risca.”",
					"data" => "DISCIPLINA",
					"imagem" => "13.png",
			),
			array(
					"pergunta" => "“Pratico a empatia o tempo todo, por isso muitos me procuram para buscar aquele ombro amigo, pedir conselhos e se sentirem melhor.”",
					"data" => "EMPATIA",
					"imagem" => "14.png",
			),
			array(
					"pergunta" => "“Tenho sede de aprender coisas novas a todo tempo. Leio, aprendo, cresço e isso me prepara para os desafios e para estar sempre antenado.”",
					"data" => "ESTUDIOSO",
					"imagem" => "15.png",
			),
			array(
					"pergunta" => "“Tudo que me proponho a fazer eu faço direito. Busco a excelência, invisto nos meus talentos, e em equipe costumo me sentir o mais bem preparado.”",
					"data" => "EXCELÊNCIA",
					"imagem" => "16.png",
			),
			array(
					"pergunta" => "“Tenho uma dose extra de foco para estabelecer minhas prioridades de vida. Na minha agenda eu tenho um tempo para planejar minha semana e até meu ano.”",
					"data" => "FOCO",
					"imagem" => "17.png",
			),
			array(
					"pergunta" => "“Olhar para frente e imaginar o amanhã me fascina. Falo da minha visão de futuro e gosto de bater papo com pessoas que se preocupam com o que está por vir como eu.”",
					"data" => "FUTURISTA",
					"imagem" => "18.png",
			),
			array(
					"pergunta" => "“Paz e amor é o que eu quero para nós. Me relaciono bem com as pessoas, levo harmonia, uso palavras simples e sempre evito conflitos.”",
					"data" => "HARMONIA",
					"imagem" => "19.png",
			),
			array(
					"pergunta" => "“Sou um empreendedor. Costumo ter mais ideias de valor do que a maioria das pessoas, descubro novas formas de fazer as coisas o tempo todo.”",
					"data" => "IDEATIVO",
					"imagem" => "20.png",
			),
			array(
					"pergunta" => "“As pessoas são diferentes, mas eu não trato ninguém com diferença. Acho que esse é meu maior dom: igualdade.”",
					"data" => "IMPARCIALIDADE",
					"imagem" => "21.png",
			),
			array(
					"pergunta" => "“Meu lema é inclusão e não exclusão. Aceito muitos tipos de pessoas e odeio quando as pessoas são deixadas de lado.”",
					"data" => "INCLUSÃO",
					"imagem" => "22.png",
			),
			array(
					"pergunta" => "“Tenho facilidade em identificar que ninguém é igual a ninguém e mais que isso, compreender e valorizar o que é único num indivíduo.”",
					"data" => "INDIVIDUALIZAÇÃO",
					"imagem" => "23.png",
			),
			array(
					"pergunta" => "“Tenho uma lente poderosa que me ajuda a observar o mundo melhor e a captar informações, aprendizado e a fazer conexões.”",
					"data" => "INPUT",
					"imagem" => "24.png",
			),
			array(
					"pergunta" => "“A minha melhor companhia sou eu. Gosto de fazer as coisas sozinho, de pensar mais e falar menos.”",
					"data" => "INTELECÇÃO",
					"imagem" => "25.png",
			),
			array(
					"pergunta" => "“Adoro arrumar confusão. Calma, não que eu seja bagunceiro, mas se teve algum “pt“ aí me chama que eu ajudo a buscar a melhor solução.”",
					"data" => "ORGANIZAÇÃO",
					"imagem" => "26.png",
			),
			array(
					"pergunta" => "“Sou um pensador estratégico. Quando olho para um problema logo consigo enxergar todos os fatores que o afetam e a melhor solução.”",
					"data" => "PENSAMENTO ESTRATÉGICO",
					"imagem" => "27.png",
			),
			array(
					"pergunta" => "“Sou cheio de alegria, de entusiasmo, sou feliz e essa minha felicidade contagia os outros. Gosto de incentivar e elogiar as pessoas a minha volta.”",
					"data" => "POSITIVO",
					"imagem" => "28.png",
			),
			array(
					"pergunta" => "“Tenho os pés bem fixados ao chão e em todas as minhas escolhas gosto de pensar com cautela e ter certeza para depois agir e selecionar, inclusive os amigos.”",
					"data" => "PRUDÊNCIA",
					"imagem" => "29.png",
			),
			array(
					"pergunta" => "“Meu relógio não para. Sempre trabalhei duro, tenho uma resistência superior quando o assunto é entregar resultados e impactar.”",
					"data" => "REALIZAÇÃO",
					"imagem" => "30.png",
			),
			array(
					"pergunta" => "“Prefiro trabalhar em equipe e adoro cultivar relacionamentos de troca. Sou sensível aos sentimentos dos outros, sou procurado para aconselhar.”",
					"data" => "RELACIONAMENTO",
					"imagem" => "31.png",
			),
			array(
					"pergunta" => "“Quando visto a camisa é pra valer. Sou muito responsável e quando prometo algo me dedico 100% para cumprir.”",
					"data" => "RESPONSABILIDADE",
					"imagem" => "32.png",
			),
			array(
					"pergunta" => "“Todo problema tem uma solução. E a melhor sensação é de que consigo resolvê-los, identificando as causas e trazendo as coisas de volta à vida.”",
					"data" => "RESTAURAÇÃO",
					"imagem" => "33.png",
			),
			array(
					"pergunta" => "“Gosto de ser conhecido, de receber reconhecimento e de saber que as pessoas apreciam meus pontos fortes únicos. Tenho sede de ser bem-sucedido.”",
					"data" => "SIGNIFICÂNCIA",
					"imagem" => "34.png",
			),
			);
		//echo'<pre>'; print_r($this->view['perguntas']); exit;
		
 	}
	
 	protected function salvar()
	{
		
		$post = $this->params()->fromPost();
		
		$model = new ModelPontosFortes($this->tb, $this->adapter);
		
		//echo '<pre>'; print_r(key($post['like'])); exit;
		
		foreach ( $post['like'] as $key=>$option ) {
			//save 	
			$params['id_login'] = $this->layout()->me->id_login;
			$params['value'] = $option;
			$params['key'] = $key;
			
			//echo '<pre>'; print_r($params['key']); exit;
			
			$model->save($params);
			
		}
		
		$params['pontos_fortes'] = "Completo";
		$modellogin = new ModelLogin($this->tb, $this->adapter);
		$modellogin->save($params, $this->layout()->me->id_login);
		
		//redirect
		return $this->redirect()->toUrl('/trilha5/sucesso');
		
		
	}
    
    public function indexAction()
    {
    	
    	$this->init();
    	
    	$breadcrumb = [];
    	$breadcrumb['Inicio'] = 'https://wantu.com.br';
    	$breadcrumb['Minha conta'] = 'https://wantu.com.br/minha-conta';
    	$breadcrumb['Quais são seus talentos extraordinários?'] = 'https://wantu.com.br/trilha5';
    	$this->layout()->setVariable('breadcrumb', $breadcrumb);
    	
    	
    	
    	//echo '<pre>'; print_r($this->layout()->me->id_login); exit;
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/init.js');
    	$this->head->setJs('extensions/jquery.perfect.scrollbar/js/perfect-scrollbar.jquery.min.js');
    	
    	return new ViewModel($this->view);
    }
    
    public function sucessoAction()
    {
    	//params
    	$where = "perfilComportamental.id_login = '" . $this->layout()->me->id_login. "'";
    	$model = new ModelPerfilComportamental($this->tb, $this->adapter);
    	$this->view['result'] = $model->get(["expr" => $where]);
    	$this->view['result'] = $model->resultado($this->view['result']->toArray());
    	//echo'<pre>'; print_r($this->view['result']); exit;
    	
    	return new ViewModel($this->view);
    }
}

