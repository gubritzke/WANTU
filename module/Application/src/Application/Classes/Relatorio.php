<?php
namespace Application\Classes;

class Relatorio
{
	private $competencias_total = array(
		'cp1' => 2.20,
		'cp2' => 2.10,
		'cp3' => 2.10,
		'cp4' => 2.20,
		'cp5' => 2.20,
		'cp6' => 2.10,
		'cp7' => 2.20,
		'cp8' => 2.10,
		'cp9' => 2.10,
		'cp10' => 2.10,
		'cp11' => 2.10,
		'cp12' => 2.20,
		'cp13' => 2.10,
		'cp14' => 2.10,
		'cp15' => 2.20,
	);
	
	private $carreiras_total = array(
		'academica' => 2.17,
		'publica' => 2.10,
		'especialista' => 2.15,
		'empreendedora' => 2.17,
		'politica' => 2.16,
		'gerencial' => 2.15,
	);
	
	//definir carreiras
	private $carreira_academica = array(
		'cp1' => [25, 0.55],
		'cp2' => 0,
		'cp3' => 0,
		'cp4' => 0,
		'cp5' => [10, 0.22],
		'cp6' => [10, 0.21],
		'cp7' => [25, 0.55],
		'cp8' => 0,
		'cp9' => [10, 0.21],
		'cp10' => 0,
		'cp11' => [10, 0.21],
		'cp12' => 0,
		'cp13' => 0,
		'cp14' => 0,
		'cp15' => [10, 0.22],
	);
	private $carreira_empreendedora = array(
		'cp1' => 0,
		'cp2' => [10, 0.21],
		'cp3' => [25, 0.53],
		'cp4' => 0,
		'cp5' => 0,
		'cp6' => [25, 0.53],
		'cp7' => 0,
		'cp8' => [10, 0.21],
		'cp9' => [10, 0.21],
		'cp10' => [10, 0.21],
		'cp11' => 0,
		'cp12' => 0,
		'cp13' => [10, 0.21],
		'cp14' => 0,
		'cp15' => 0,
	);
	private $carreira_gerencial = array(
		'cp1' => 0,
		'cp2' => 0,
		'cp3' => 0,
		'cp4' => [25, 0.55],
		'cp5' => [10, 0.22],
		'cp6' => 0,
		'cp7' => 0,
		'cp8' => [10, 0.21],
		'cp9' => [10, 0.21],
		'cp10' => 0,
		'cp11' => 0,
		'cp12' => [10, 0.22],
		'cp13' => [25, 0.53],
		'cp14' => [10, 0.21],
		'cp15' => 0,
	);
	private $carreira_publica = array(
		'cp1' => [10, 0.22],
		'cp2' => 0,
		'cp3' => 0,
		'cp4' => [10, 0.22],
		'cp5' => 0,
		'cp6' => 0,
		'cp7' => [10, 0.22],
		'cp8' => 0,
		'cp9' => 0,
		'cp10' => 0,
		'cp11' => [25, 0.53],
		'cp12' => [25, 0.55],
		'cp13' => [10, 0.21],
		'cp14' => 0,
		'cp15' => [10, 0.22],
	);
	private $carreira_politica = array(
		'cp1' => 0,
		'cp2' => [10, 0.21],
		'cp3' => 0,
		'cp4' => [10, 0.22],
		'cp5' => [10, 0.22],
		'cp6' => 0,
		'cp7' => 0,
		'cp8' => [25, 0.53],
		'cp9' => 0,
		'cp10' => [10, 0.21],
		'cp11' => 0,
		'cp12' => [10, 0.22],
		'cp13' => 0,
		'cp14' => 0,
		'cp15' => [25, 0.55],
	);
	private $carreira_especialista = array(
		'cp1' => [10, 0.21],
		'cp2' => [10, 0.21],
		'cp3' => 0,
		'cp4' => 0,
		'cp5' => 0,
		'cp6' => [10, 0.21],
		'cp7' => [25, 0.55],
		'cp8' => 0,
		'cp9' => 0,
		'cp10' => [25, 0.53],
		'cp11' => [10, 0.21],
		'cp12' => 0,
		'cp13' => 0,
		'cp14' => 0,
		'cp15' => [10, 0.22],
	);
	
	//definir tipos
	private $im			= 10;
	private $ancoras	= 20;
	private $perfil		= 30;
	private $pontos_f	= 30;
	private $aa_comp	= 10;
	
	//definir pontuacões do tipo IM
	private $logico_matematico 		= array('cp1', 'cp7', 'cp10', 'cp13');
	private $verbal 				= array('cp4', 'cp5', 'cp12', 'cp14');
	private $corporal_cinestesica 	= array('cp2', 'cp3', 'cp9', 'cp10');
	private $musical 				= array('cp5', 'cp6', 'cp7', 'cp11');
	private $espacial 				= array('cp3', 'cp6', 'cp13', 'cp15');
	private $intrapessoal 			= array('cp1', 'cp7', 'cp8', 'cp11');
	private $interpessoal 			= array('cp4', 'cp5', 'cp8', 'cp12');
	private $naturalista 			= array('cp2', 'cp9', 'cp14', 'cp15');
	private $existencial 			= array('cp1', 'cp4', 'cp12', 'cp15');
	
	//definir pontuacões do tipo Aptidão(ancora)
	//Técnica ou Funcional não existe
	private $gerencial 				= array('cp4', 'cp5', 'cp8', 'cp10', 'cp13', 'cp14');
	private $seguranca				= array('cp1', 'cp7', 'cp11', 'cp15');
	private $empreendedorismo		= array('cp2', 'cp3', 'cp4', 'cp6', 'cp8', 'cp13');
	private $autonomia				= array('cp2', 'cp3', 'cp10', 'cp13');
	private $dedicacao				= array('cp4', 'cp5', 'cp8', 'cp9', 'cp12', 'cp14');
	private $desafio				= array('cp1', 'cp3', 'cp6', 'cp7', 'cp9', 'cp10');
	private $estilo					= array('cp9', 'cp11', 'cp12', 'cp15');
	private $internacional			= array('cp2', 'cp5', 'cp12', 'cp15');
	private $especialista			= array('cp1', 'cp6', 'cp7', 'cp11', 'cp14');
	
	//definir pontuacões do tipo Pontos Fortes
	private $adaptabilidade			= array('cp2');
	private $analítico				= array('cp15');
	private $ativacao				= array('cp3', 'cp8');
	private $auto_afirmacao			= array('cp13');
	private $carisma				= array('cp5', 'cp12');
	private $comando				= array('cp4');
	private $competicao				= array('cp10');
	private $comunicacao			= array('cp5', 'cp14');
	private $conexao				= array('cp12');
	private $contexto				= array('cp13');
	private $crenca					= array('cp8');
	private $desenvolvimento		= array('cp8');
	private $disciplina				= array('cp11');
	private $empatia				= array('cp5');
	private $estudioso				= array('cp1');
	private $excelencia				= array('cp7');	
	private $foco					= array('cp10', 'cp11');
	private $futurista				= array('cp6');
	private $harmonia				= array('cp2');
	private $ideativo				= array('cp1', 'cp6');
	private $imparcialidade			= array('cp4');
	private $inclusao				= array('cp4', 'cp14');
	private $individualizacao		= array('cp8', 'cp14');
	private $input					= array('cp6');
	private $inteleccao				= array('cp1', 'cp15');
	private $organizacao			= array('cp2', 'cp13');
	private $pensamento_estrategico	= array('cp15');
	private $positivo				= array('cp9');
	private $prudencia				= array('cp7', 'cp11');
	private $realizacao				= array('cp9', 'cp10');
	private $relacionamento			= array('cp10');
	private $responsabilidade		= array('cp7');
	private $restauracao			= array('cp3');
	private $significancia			= array('cp3');
	
	//definir pontuacões do tipo Perfil
	private $perfil_gosta_de_privacidade			= array('cp1');
	private $perfil_racional						= array('cp1');
	private $perfil_sabe_se_preservar				= array('cp1');
	private $perfil_suave_tem_tato					= array('cp2');
	private $perfil_alto_nivel_de_exigencia			= array('cp7');
	private $perfil_meticuloso						= array('cp7');
	private $perfil_obedece_as_regras				= array('cp7');
	private $perfil_orientado_para_detalhes			= array('cp7');
	private $perfil_perfeccionista					= array('cp7');
	private $perfil_organizado						= array('cp11');
	private $perfil_planeja_com_antecedencia		= array('cp11');
	private $perfil_previsivel						= array('cp11');
	private $perfil_leal							= array('cp12');
	private $perfil_considera_as_opcoes				= array('cp13');
	private $perfil_cauteloso_na_acao				= array('cp15');
	private $perfil_ordeiro							= array('cp15');
	private $perfil_sistematico						= array('cp15');
	private $perfil_curioso							= array('cp1');
	private $perfil_impulsivo						= array('cp2');
	private $perfil_confiante						= array('cp3');
	private $perfil_persuasivo						= array('cp4');
	private $perfil_bom_comunicador					= array('cp5');
	private $perfil_diz_a_coisa_certa				= array('cp5');
	private $perfil_espontaneo						= array('cp5');
	private $perfil_gosta_de_pessoas				= array('cp5');
	private $perfil_senso_de_humor					= array('cp5');
	private $perfil_gosta_de_variedade				= array('cp6');
	private $perfil_inovador						= array('cp6');
	private $perfil_alma_da_festa					= array('cp9');
	private $perfil_animado							= array('cp9');
	private $perfil_energetico						= array('cp9');
	private $perfil_simpatico						= array('cp12');
	private $perfil_sociavel						= array('cp12');
	private $perfil_não_orientado_para_detalhes		= array('cp15');
	private $perfil_percebe_o_quadro_geral			= array('cp15');
	private $perfil_facilmente_entediado			= array('cp2');
	private $perfil_assertivo						= array('cp3');
	private $perfil_assume_riscos					= array('cp3');
	private $perfil_corajoso						= array('cp3');
	private $perfil_independente					= array('cp3');
	private $perfil_criador_de_alternativas			= array('cp6');
	private $perfil_criativo						= array('cp6');
	private $perfil_extrovertido					= array('cp6');
	private $perfil_influenciador					= array('cp8');
	private $perfil_toma_a_dianteira				= array('cp8');
	private $perfil_cheio_de_energia				= array('cp9');
	private $perfil_rapido							= array('cp9');
	private $perfil_ambicioso						= array('cp10');
	private $perfil_competititvo					= array('cp10');
	private $perfil_determinado						= array('cp10');
	private $perfil_orientado_para_resultados		= array('cp10');
	private $perfil_controlador						= array('cp11');
	private $perfil_decidido						= array('cp13');
	private $perfil_forte_opiniao					= array('cp13');
	private $perfil_comprometido					= array('cp14');
	private $perfil_reservado						= array('cp1');
	private $perfil_antecipa_necessidades			= array('cp2');
	private $perfil_preocupase_com_os_outros		= array('cp2');
	private $perfil_construtor_de_confianca			= array('cp4');
	private $perfil_esquivase_de_conflito			= array('cp4');
	private $perfil_mediador						= array('cp4');
	private $perfil_paciente						= array('cp4');
	private $perfil_lider 							= array('cp8');
	private $perfil_mentor							= array('cp8');
	private $perfil_positivo						= array('cp8');
	private $perfil_objetivo						= array('cp10');
	private $perfil_metodico						= array('cp11');
	private $perfil_agradavel						= array('cp12');
	private $perfil_amigavel						= array('cp12');
	private $perfil_bem_informado					= array('cp13');
	private $perfil_cuidadoso						= array('cp13');
	private $perfil_compreensivo					= array('cp14');
	private $perfil_cooperativo						= array('cp14');
	private $perfil_empatico						= array('cp14');
	private $perfil_facil_de_lidar					= array('cp14');
	
	
	
	
	//	Calcula as pontuacoes do 
	//
	
	
	public function getPontuacao($pontuacoes, $tipo)
	{
		$result = array();
		
		foreach( $pontuacoes as $pontuacao )
		{
			$pontuacao = \Naicheframework\Helper\Convert::removeEspecialChars($pontuacao);
			$pontuacao = mb_strtolower($pontuacao);
			$pontuacao= str_replace(['-'], '_', $pontuacao);
			
// 			if( empty($this->$pontuacao) )
// 			{
// 				echo $pontuacao; exit;
// 			}
			
            if( !empty($this->$pontuacao) ){
    			foreach( $this->$pontuacao as $value )
    			{
    				if( !isset($result[$value]) )
    				{
    					$result[$value] = 0;
    				}
    				
    				$result[$value]++;
    			}
			}
		}
		//Array([cp2] => 1,	[cp9] => 2)
		//echo'<pre>'; print_r($result); exit;

		foreach( $result as &$value )
		{
			$value = $value * $this->$tipo / 100;
		}
		
		return $result;
	}
	
	public function getPontuacaoCompetencias($pontuacoes)
	{
		$result = array(
			'cp1' 	=> ($pontuacoes['p1'] / 4),
			'cp2' 	=> ($pontuacoes['p2'] / 4),
			'cp3' 	=> ($pontuacoes['p3'] / 4),
			'cp4' 	=> ($pontuacoes['p4'] / 4),
			'cp5' 	=> ($pontuacoes['p5'] / 4),
			'cp6' 	=> ($pontuacoes['p6'] / 4),
			'cp7' 	=> ($pontuacoes['p7'] / 4),
			'cp8' 	=> ($pontuacoes['p8'] / 4),
			'cp9' 	=> ($pontuacoes['p9'] / 4),
			'cp10' 	=> ($pontuacoes['p10'] / 4),
			'cp11' 	=> ($pontuacoes['p11'] / 4),
			'cp12' 	=> ($pontuacoes['p12'] / 4),
			'cp13' 	=> ($pontuacoes['p13'] / 4),
			'cp14' 	=> ($pontuacoes['p14'] / 4),
			'cp15' 	=> ($pontuacoes['p15'] / 4),
		);
		
		foreach( $result as &$value )
		{
			$value = $value * $this->aa_comp / 100;
		}
		
		return $result;
	}
	
	public function getPontuacaoPerfil($competencias)
	{
		$result = array();
		
		foreach( $competencias as $competencia )
		{
			$competencia = \Naicheframework\Helper\Convert::removeEspecialChars($competencia);
			$competencia = "perfil_" . mb_strtolower($competencia);
			$competencia = str_replace(['-'], '_', $competencia);
			
			foreach( $this->$competencia as $key )
			{
				if( empty($result[$key]) )
				{
					$result[$key] = 0;
				}
				
				$result[$key] += 0.2;
			}
		}
		
		foreach( $result as &$value )
		{
			$value = $value * $this->perfil / 100;
		}
		
		return $result;
	}
	
	public function somarCP($results)
	{
		$cps = array(
			'cp1' => 0,
			'cp2' => 0,
			'cp3' => 0,
			'cp4' => 0,
			'cp5' => 0,
			'cp6' => 0,
			'cp7' => 0,
			'cp8' => 0,
			'cp9' => 0,
			'cp10' => 0,
			'cp11' => 0,
			'cp12' => 0,
			'cp13' => 0,
			'cp14' => 0,
			'cp15' => 0,
		);
		
		foreach( $results as $result )
		{
			foreach( $result as $key => $value )
			{
				$cps[$key] += $value;
			}
		}
		
		return $cps;
	}
	
	public function porcentagemTotalCP($cps)
	{
		foreach( $cps as $key => &$value )
		{
			$value = $value * 100 / $this->competencias_total[$key];
			$value = number_format($value, 2);
		}
		
		return $cps;
	}
	
	public function porcentagemTotalCarreiras($cps)
	{
		$carreiras = array();
		
		foreach( $cps as $key => $value )
		{
			//carreira academica
			$porcentagem = $this->carreira_academica[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['academica'][$key] = number_format($final, 2);
			
			//carreira_empreendedora
			$porcentagem = $this->carreira_empreendedora[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['empreendedora'][$key] = number_format($final, 2);
			
			//carreira_gerencial
			$porcentagem = $this->carreira_gerencial[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['gerencial'][$key] = number_format($final, 2);
			
			//carreira_publica
			$porcentagem = $this->carreira_publica[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['publica'][$key] = number_format($final, 2);;
			
			//carreira_politica
			$porcentagem = $this->carreira_politica[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['politica'][$key] = number_format($final, 2);
			
			//carreira_especialista
			$porcentagem = $this->carreira_especialista[$key][0];
			$final = $porcentagem * $value / 100;
			$carreiras['especialista'][$key] = number_format($final, 2);
		}
		
		foreach( $carreiras as $key => $carreira )
		{
			$total = array_sum($carreira) * 100 / $this->carreiras_total[$key];
			$carreiras[$key] = number_format($total, 2);
		}
		
		return $carreiras;
	}
	
	public function definirCarreiras($cps)
	{
		$carreiras = array();
		
		foreach( $cps as $key => $value )
		{
			//carreira academica
			$porcentagem = $this->carreira_academica[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_academica[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_academica[$key][1];
				$carreiras['academica'][$key] = number_format($final, 2);;
			}
			
			//carreira_empreendedora
			$porcentagem = $this->carreira_empreendedora[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_empreendedora[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_empreendedora[$key][1];
				$carreiras['empreendedora'][$key] = number_format($final, 2);;
			}
			
			//carreira_gerencial
			$porcentagem = $this->carreira_gerencial[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_gerencial[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_gerencial[$key][1];
				$carreiras['gerencial'][$key] = number_format($final, 2);
			}
			
			//carreira_publica
			$porcentagem = $this->carreira_publica[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_publica[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_publica[$key][1];
				$carreiras['publica'][$key] = number_format($final, 2);;
			}
			
			//carreira_politica
			$porcentagem = $this->carreira_politica[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_politica[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_politica[$key][1];
				$carreiras['politica'][$key] = number_format($final, 2);;
			}
			
			//carreira_especialista
			$porcentagem = $this->carreira_especialista[$key][0];
			$final = $porcentagem * $value / 100;
			if( !empty($this->carreira_especialista[$key][1]) )
			{
				$final = $final * 100 / $this->carreira_especialista[$key][1];
				$carreiras['especialista'][$key] = number_format($final, 2);;
			}
		}
		
// 		foreach( $carreiras as $key => $carreira )
// 		{
// 			$carreiras[$key]['total'] = array_sum($carreira);
// 		}
		
		return $carreiras;
	}
	
	public static function getCompetenciaNome($value)
	{
		switch ( $value )
		{
			case 'cp1': return 'AutoDesenvolvimento e Gestão do Conhecimento';
			case 'cp2': return 'Capacidade de Adaptação e Flexibilidade';
			case 'cp3': return 'Capacidade Empreendedora';
			case 'cp4': return 'Capacidade Negocial';
			case 'cp5': return 'Comunicação e Interação';
			case 'cp6': return 'Criatividade e Inovação';
			case 'cp7': return 'Cultura da Qualidade';
			case 'cp8': return 'Liderança';
			case 'cp9': return 'Motivação e Energia para o trabalho';
			case 'cp10': return 'Orientação para resultados';
			case 'cp11': return 'Planejamento e Organização';
			case 'cp12': return 'Relacionamento Interpessoal';
			case 'cp13': return 'Tomada de Decisão';
			case 'cp14': return 'Trabalho em Equipe';
			case 'cp15': return 'Visão Sistêmica';
			
		}
	}
}