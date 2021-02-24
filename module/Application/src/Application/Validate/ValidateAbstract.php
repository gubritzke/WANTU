<?php
namespace Application\Validate;

/**
 * @author: Vitor Deco
 */
abstract class ValidateAbstract
{
    //lista de erros
    const ERROR_REQUIRED = "Preencha todos os campos obrigatórios!";
    const ERROR_SENHA_CONFIRMAR = "A confirmação da senha está incorreta!";
    const ERROR_EMAIL_DUPLICIDADE = "O e-mail informado já se encontra cadastrado em nossos registros!";
    const ERROR_CPF_DUPLICIDADE = "O CPF informado já se encontra cadastrado em nossos registros!";
    const ERROR_EMAIL_REAL = "O e-mail informado não está correto!";
    const ERROR_CNPJ = "O CNPJ informado não é válido!";
    const ERROR_CPF = "O CPF informado não é válido!";
    const ERROR_TERMOS = "É necessário ler e aceitar os termos de uso para continuar!";
    const ERROR_DATA_DE_NASCIMENTO ="Preencha sua data de nascimento";
    const ERROR_SEXO ="Preencha o seu sexo";
    const ERROR_ESTADO ="Preencha seu estado";
    const ERROR_FILHOS ="Preencha a quantidade de filhos";
    const ERROR_NACIONALIDADE ="Preencha sua nacionalidade";
    const ERROR_CEP ="Preencha seu CEP";
    const ERROR_ENDERECO ="Preencha seu endereço";
    const ERROR_NUMERO ="Preencha o numero da sua casa";
    const ERROR_BAIRRO ="Preencha seu bairro";
    const ERROR_CIDADE ="Preencha sua cidade";
    const ERROR_TELEFONE_CELULAR ="Preencha seu numero de telefone";
    const ERROR_RG ="Preencha seu rg";
    const ERROR_ORGAO_EMISSOR ="Preencha o orgão emissor do seu rg";
    const ERROR_OBJETIVO_PROFISSIONAIS ="Preencha os seus objetivos profissionais";
    const ERROR_POSSUI_CNH ="Preencha se você possui CNH";
    const ERROR_E_FUMANTE ="Preencha se você é fumante";
    const ERROR_ALGUMA_DEFICIENCIA ="Preencha se possui alguma deficiência";
    const ERROR_POSSUI_CONTA_BANCARIA ="Preencha se possui conta bancária";
    const ERROR_POSSUI_CARTAO_DE_CREDITO ="Preencha se possui cartão de crédito";
    const ERROR_QUANTAS_PESSOAS_MORAM_EM_SEU_DOMICILIO ="Preencha quantas pessoas moram em seu domicilio";
    const ERROR_QUANTAS_PESSOAS_TRABALHAM_EM_SEU_DOMICILIO ="Preencha quantas pessoas que moram em seu domocilio trabalham";
    const ERROR_COMO_VOCE_AVIALIA_SEU_CONVIVIO_FAMILIAR ="Preencha como você avalia seu convivio familiar";
    const ERROR_SEU_DOMICILIO_E ="Preencha o seu tipo de domicilio";
    const ERROR_RENDA_TOTAL ="Preencha a sua renda total";
    const ERROR_COMO_CONHECEU_O_WANTU ="Preencha como você conheceu o Wantu";
    const ERROR_P1 ="Preencha todas as perguntas";
    const ERROR_P2 ="Preencha todas as perguntas";
    const ERROR_P3 ="Preencha todas as perguntas";
    const ERROR_P4 ="Preencha todas as perguntas";
    const ERROR_P5 ="Preencha todas as perguntas";
    const ERROR_P6 ="Preencha todas as perguntas";
    const ERROR_P7 ="Preencha todas as perguntas";
    const ERROR_P8 ="Preencha todas as perguntas";
    const ERROR_P9 ="Preencha todas as perguntas";
    const ERROR_P10 ="Preencha todas as perguntas";
    const ERROR_P11 ="Preencha todas as perguntas";
    const ERROR_P12 ="Preencha todas as perguntas";
    const ERROR_P13 ="Preencha todas as perguntas";
    const ERROR_P14 ="Preencha todas as perguntas";
    const ERROR_P15 ="Preencha todas as perguntas";
    const ERROR_TELEFONE_EMPRESA ="Preencha o número de telefone";
    const ERROR_EMPRESA_EMPRESA ="Preencha o nome da sua empresa";
    
    public static $tb;
    public static $adapter;
    
    /**
     * está função retorna uma string com a ordenação correta para enviar na query
     * @param array $columns
     * @param array $sort
     * @return string
     */
    public static function sort($columns, $sort)
    {
        $sort = explode("-", $sort);
        $order = $sort[0];
        $direction = (isset($sort[1]) && $sort[1] == "asc" ? "desc" : "asc");
        $return = (in_array($order, $columns) ? $order : (!empty($columns[$order]) ? $columns[$order] : null));
    
        return $return . " " . $direction;
    }
}