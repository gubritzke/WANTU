<?php
namespace Application\Controller;
use Application\Classes\GlobalController;
use Application\Model\ModelPlanosUsuario;
use Application\Classes\MailMessage;

class RotinaController extends GlobalController
{
    protected function init()
    {
        //validar token
        $token = $this->params()->fromQuery('token');
        if( $token != 'N@!CH3' ) die('Acesso restrito!');
    }
    
    /**
     * /rotina/pagamento-atualizar-status?token=N@!CH3
     * atualiza o status do pagamento consultando na API do Gateway
     * executado de 30 em 30 minutos
     */
    public function pagamentoAtualizarStatusAction()
    {
        $this->init();
         
        //selecionar planos com pagamento pendente
        $limit = null;
        $where = "planos_usuario.status = '0'";
        $model = new ModelPlanosUsuario($this->tb, $this->adapter);
        $result = $model->get2(['where'=>$where, 'limit'=>$limit])->toArray();
        
        //instancia do MOIP
        $payment = new \Naicheframework\Shopping\Payment\PaymentMoipSplit($this->layout()->config_payment['moip']);
        if( in_array($this->layout()->config_host['env'], ['local','homolog']) ) $payment->setSandbox(true);
         
        //loop nos planos
        foreach( $result as $row )
        {
            $row = (object)$row;
            
            //json to array
            $row->gateway= json_decode($row->gateway);
            	
            //consultar o status no gateway
            try 
            {
                //verificar se existe o ID do pagamento
            	if( empty($row->gateway->id) ) throw new \Exception('ID do pagamento não encontrado!');
                 
                //consultar transação no gateway
            	$transaction = $payment->getTransaction($row->gateway->id);
                if( !isset($transaction->status) ) throw new \Exception('O gateway de pagamento não retornou o status do pagamento!');
                 
            } catch ( \Exception $e ){
                 
                //registrar log de erro
                \Naicheframework\Log\Log::error("Erro ao atualizar status de pagamento", ["exception"=>$e->getMessage()]);
                 
                //em caso de exceção pular o pedido
                continue;
            }
            
            //verificar se status foi alterado
            if( $transaction->status != $row->gateway->status )
            {
                //atualizar gateway response
            	$row->gateway->status = $transaction->status;
                
                //salvar status pagamento
                $set = array();
                $set['status'] = $transaction->status;
                $set['gateway'] = json_encode($row->gateway);
                $model = new ModelPlanosUsuario($this->tb, $this->adapter);
                $model->save($set, $row->id_planos_usuario);
                
                //enviar email notificando
                if( $transaction->status == 1 )
                {
	                $replace = array('nome' => $row->nome);
	                $mail = new MailMessage();
	                $mail->planoPago($row->email, $replace);
	                
                } else if( $transaction->status == 2 ){
                	
                	$replace = array('nome' => $row->nome);
                	$mail = new MailMessage();
                	$mail->planoCancelado($row->email, $replace);
                }
                
                
            }
        }
         
        die('Concluído com sucesso! =)');
    }
}