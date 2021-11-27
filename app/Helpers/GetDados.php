<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 29/12/2017
 * Time: 14:52
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/04/2020
 * Time: 14:30
 */

namespace App\Helpers;

#use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Log;

class GetDados {

	public static function getDadosFibraCall($config_tecnicas){
		if(isset($config_tecnicas->sip_pulse->profile) && isset($config_tecnicas->sip_pulse->dominio) && isset($config_tecnicas->sip_pulse->rateplan)){
			$rede_acesso = "TipoAcesso";
			$dados_fibra = [
				'dominio' => isset($config_tecnicas->sip_pulse->dominio) ? $config_tecnicas->sip_pulse->dominio : 'Não cadastrado',
				'tipo_acesso' => isset($config_tecnicas->$rede_acesso) ? $config_tecnicas->$rede_acesso : 'Não cadastrado',
				'numero_canais' => isset($config_tecnicas->sip_pulse->channels) ? $config_tecnicas->sip_pulse->channels : 'Não cadastrado',
				'profile' => isset($config_tecnicas->sip_pulse->profile) ? $config_tecnicas->sip_pulse->profile : 'Não cadastrado',
				'proxy' => isset($config_tecnicas->SBC) ? $config_tecnicas->SBC : '-',
				'p_quota_diaria' => isset($config_tecnicas->sip_pulse->has_daily_quota) ? ($config_tecnicas->sip_pulse->has_daily_quota ? "Sim" : "Não") : 'Não cadastrado',
				'plano_tarifa' => isset($config_tecnicas->sip_pulse->rateplan) ? $config_tecnicas->sip_pulse->rateplan : 'Não cadastrado',
				'piloto' => isset($config_tecnicas->sip_pulse->username) ? $config_tecnicas->sip_pulse->username : 'Não cadastrado',
				'quota_diaria' => isset($config_tecnicas->sip_pulse->quota_limit) ? $config_tecnicas->sip_pulse->quota_limit : 'Não cadastrado',
				'cadencia' => isset($config_tecnicas->sip_pulse->cadencia) ? $config_tecnicas->sip_pulse->cadencia : 'Não cadastrado',
				'consumo_parcial' => '-',
				'consumo_quota' => isset($config_tecnicas->sip_pulse->quota_usage) ? $config_tecnicas->sip_pulse->quota_usage : '-'
			];
		}else{
			$dados_fibra = false;
		}
		return $dados_fibra;
	}
	public static function getDadosMediaGateway($config_tecnicas){
		if(isset($config_tecnicas->mg_data)){
			$dados_media = [
				'identificador' => isset($config_tecnicas->mg_data->id) ? $config_tecnicas->mg_data->id : '',
				'endereco_ip' => isset($config_tecnicas->mg_data->ip) ? $config_tecnicas->mg_data->ip : '',
				'canais' => isset($config_tecnicas->mg_data->channels) ? $config_tecnicas->mg_data->channels  : '',
				'modelo' => isset($config_tecnicas->mg_data->model) ? $config_tecnicas->mg_data->model  : '',
				'sinalizacao' => isset($config_tecnicas->mg_data->signaling) ? $config_tecnicas->mg_data->signaling : '',
				'fornecedor' => isset($config_tecnicas->mg_data->vendor) ? $config_tecnicas->mg_data->vendor : ''
			];
		}else{
			$dados_media = false;
		}
		return $dados_media;
	}
	public static function getDadosEnderecamentos($config_tecnicas){
		$enderecamentos = [];
		if(isset($config_tecnicas->Enderecamento_ip) && isset($config_tecnicas->Enderecamento_ip->networks)){
			foreach ($config_tecnicas->Enderecamento_ip->networks as $enderecamento){
				$enderecamentos[] = [
					'network' => $enderecamento->network,
					'gateway' => $enderecamento->gateway,
					'usable' => $enderecamento->usable,
					'mask' => $enderecamento->mask
				];
			}
		}else{
			$enderecamentos = false;
		}
		return $enderecamentos;
	}
	public static function getDadosEnderecamentoIPV6($config_tecnicas){
		$enderecamentos_ipv6 = [];
		if(isset($config_tecnicas->Enderecamento_ip) && isset($config_tecnicas->Enderecamento_ip->ipv6_networks)){
			foreach ($config_tecnicas->Enderecamento_ip->ipv6_networks as $enderecamento_ipv6){
				$enderecamentos_ipv6[] = [
					'gateway' => $enderecamento_ipv6->gateway,
					'ip_cliente' => $enderecamento_ipv6->client_ip
				];
			}
		}else{
			$enderecamentos_ipv6 = false;
		}
		return $enderecamentos_ipv6;
	}
	public static function getDocumentoFormated($documento){
		if(strlen($documento) == 14)
			return substr($documento, 0, 2) . '.' . substr($documento, 2, 3) .
			       '.' . substr($documento, 5, 3) . '/' .
			       substr($documento, 8, 4) . '-' . substr($documento, 12, 2);
		return substr($documento,0,3).'.'.substr($documento,3,3).'.'.substr($documento,6,3).'-'.substr($documento,9,2);
	}
	public static function getConsumoParcialParams($id, $contrato){
		if($contrato->DIA_INICIO_DE_CORTE < 10)
			$start_day = "0".$contrato->DIA_INICIO_DE_CORTE;
		else
			$start_day = $contrato->DIA_INICIO_DE_CORTE;
		$today_day = date("d");
		if($today_day < $contrato->DIA_INICIO_DE_CORTE){
			$month = (date('m')-1);
			if($month == 0){
				$month = "12";
				$year = (date('Y')-1);
				$initial_date = $year.'-'.$month.'-'.$start_day.' 00:00:00';
			}else{
				if($month < 10)
					$month = "0".$month;
				$initial_date = date('Y').'-'.$month.'-'.$start_day.' 00:00:00';
			}
		}else
			$initial_date = date('Y-m').'-'.$start_day.' '.date("H:i:s");
		$today = date("Y-m-d H:i:s");
		$paramsAPIComsumption = Array(
			'svcid' => $id,
			'start' => $initial_date,
			'end' => $today
		);
		return $paramsAPIComsumption;
	}


	public function detalhe(Request $request , $id){
		$this->beforeReturn();
        if(!$this->authenticate())
            throw new Exception('API ERROR');
        $documentos = Auth::guard('cliente_api')->user()->organizacao()->first()->documentos()->pluck('documento')->toArray();
        $params = $request->all();
        $limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 20;
        $page = (isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0) ? $params['page'] : 1;
        $paramsAPI = Array(
            'filter' => [ 'DOCUMENTO' => $documentos, "SVCID" => $id,"status" => "Habilitado"],
            'limit' => $limit,
            'page' => $page,
            'fields' => ["CONFIG_TECNICAS","SVCID", "ID_CONTRATO", "VALOR", "SVC_DESC", "PROD_DESC", "STATUS", "DATA_INSTALACAO", "DATA_REMOCAO", "DOCUMENTO","ENDERECAMENTO_IP"]
        );
        $servicos = $this->ossapi->callAPI( 'services.list', $paramsAPI , $this->auth->result->auth );
        $items = (isset($servicos->result) && isset($servicos->result->items)) ? $servicos->result->items : [];
        $data = $this->data;
        $data['id'] = $id;
        if(isset($servicos->result) && isset($servicos->result->info) && isset($servicos->result->info->number_of_rows) && $servicos->result->info->number_of_rows >= 1){
            $startDate = '';
            foreach ($items as $key => $item){
                if(isset($item->produtos)){
                    $id_contract = false;
                    $contrato = null;
                    $grafico_consumo = false;
                    $grafico_voz = false;
                    $logins = false;
                    $dados_servicos = [];
                    foreach ($item->produtos as $produto){
                        if(isset($produto->attrs->config_tecnicas)){
                            $enderecamentos = GetDados::getDadosEnderecamentos($produto->attrs->config_tecnicas);
                            $enderecamento_ipv6 = GetDados::getDadosEnderecamentoIPV6($produto->attrs->config_tecnicas);
                            $dados_media = GetDados::getDadosMediaGateway($produto->attrs->config_tecnicas);
                            $dados_fibra = GetDados::getDadosFibraCall($produto->attrs->config_tecnicas);
                            $velocidade = isset($produto->attrs->config_tecnicas->Velocidade) ? $produto->attrs->config_tecnicas->Velocidade : '';
                            $status = isset($produto->attrs->config_tecnicas->StatusOper) ? $produto->attrs->config_tecnicas->StatusOper : '';
                        }else{
                            $enderecamentos = GetDados::getDadosEnderecamentos($item->config_tecnicas);
                            $enderecamento_ipv6 = GetDados::getDadosEnderecamentoIPV6($item->config_tecnicas);
                            $dados_media = GetDados::getDadosMediaGateway($item->config_tecnicas);
                            $dados_fibra = GetDados::getDadosFibraCall($item->config_tecnicas);
                            $velocidade = isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '';
                            $status = isset($item->config_tecnicas->StatusOper) ? $item->config_tecnicas->StatusOper : '';
                        }
                        if($id_contract !== $produto->ID_CONTRATO){
                            $id_contract = $produto->ID_CONTRATO;
                            $paramsAPIContract = Array(
                                'filter' => ["ID_CONTRACT" => $produto->ID_CONTRATO ],
                                'sort' => ['-asc' => 'ID_UNICIFICADO'],
                                'limit' => 10,
                                'page' => 1,
                                'fields' => ["ID", "ID_CLIENTE", "ID_ENDERECO_CORRESP", "VALOR_SALDO_ATUAL", "STATUS", "DIA_INICIO_DE_CORTE", "ID_CONTRACT", "DOCUMENTO", "VENCIMENTO" ]
                            );
                            $contrato = $this->ossapi->callAPI( 'services.contract_list', $paramsAPIContract , $this->auth->result->auth )->result->items[0];
                        }
                        if(isset($dados_fibra)){
                            $paramsAPIComsumption = GetDados::getConsumoParcialParams($produto->SVCID,$contrato);
                            $partial_consumption = $this->ossapi->callAPI( 'services.get_partial_consumption', $paramsAPIComsumption , $this->auth->result->auth );
                            if(isset($partial_consumption->result->consumption))
                                $dados_fibra['consumo_parcial'] = $partial_consumption->result->consumption;
                        }
                        if($dados_fibra !== false)
                            $logins = true;
                        $modulo_consumo = Auth::user()->modulos()->where('id','=',7)->count() >= 1;
                        $modulo_voz = Auth::user()->modulos()->where('id','=',8)->count() >= 1;
                        if($modulo_consumo || $modulo_voz){
                            $paramsGraph = array (
                                'id' => isset($produto->TipoServico) ? $produto->TipoServico : $item->config_tecnicas->TipoServico
                            );
                            $tipoServico = $this->ossapi->callAPI( 'services.service_types', $paramsGraph , $this->auth->result->auth );
                            if(isset( $tipoServico->result) && isset($tipoServico->result->service_types) && isset($tipoServico->result->service_types[0])){
                                if($modulo_consumo && isset($tipoServico->result->service_types[0]->traffic_graph) && boolval($tipoServico->result->service_types[0]->traffic_graph))
                                    $grafico_consumo = true;
                                if($modulo_voz && isset($tipoServico->result->service_types[0]->called_graph) && boolval($tipoServico->result->service_types[0]->called_graph))
                                    $grafico_voz = true;
                            }
                        }
                        $dados_servicos = [
                            'id' => $key,
                            'velocidade' => $velocidade,
                            'status' => $status,
                            'data_ativacao' => $produto->DATA_INSTALACAO,
                            'data_vencimento' => $contrato->VENCIMENTO,
                            'periodo_apuracao' => sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE).'-'.sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE-1)
                        ];
                    }
                }

                //HISTORICO DO SERVICO
                //TODO Historico

                if(isset($item->produtos)){
                    $id_contract = reset($item->produtos)->ID_CONTRATO;
                    // Contrato
                    $paramsAPIContract = Array(
                        'filter' => ["ID_CONTRACT" => $id_contract ],
                        'sort' => ['-asc' => 'ID_UNICIFICADO'],
                        'limit' => 10,
                        'page' => 1,
                        'fields' => ["ID", "ID_CLIENTE", "ID_ENDERECO_CORRESP", "VALOR_SALDO_ATUAL", "STATUS", "DIA_INICIO_DE_CORTE", "ID_CONTRACT", "DOCUMENTO", "VENCIMENTO" ]
                    );
                    $contrato = $this->ossapi->callAPI( 'services.contract_list', $paramsAPIContract , $this->auth->result->auth )->result->items[0];
                    $data['dados_servicos'] = [
                        'id' => $key,
                        'velocidade' => isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '',
                        'status' => $item->config_tecnicas->StatusOper,
                        'data_ativacao' => reset($item->produtos)->DATA_INSTALACAO,
                        'data_vencimento' => $contrato->VENCIMENTO,
                        'periodo_apuracao' => sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE).'-'.sprintf("%02d",$contrato->DIA_INICIO_DE_CORTE-1)
                    ];
                    if($data['grafico_voz'] == true){
                        $startDate = reset($item->produtos)->DATA_INSTALACAO;
                    }
                }

                //Chamadas
                if($data['grafico_voz'] && isset($contrato)){

                    $start = \DateTime::createFromFormat('d/m/Y',$startDate);
                    $end = \DateTime::createFromFormat('d/m/Y', ($contrato->DIA_INICIO_DE_CORTE - 1).'/'.date('m').'/'.date('Y'));
                    $today = \DateTime::createFromFormat('d/m/Y',date('d/m/Y'));
                    if($today > $end)
                        $end->modify('+1month');
                    $years = 0;
                    $years += intval($start->diff($end)->format('%y%'));
                    $periodos = [];
                    $year = \DateTime::createFromFormat('Y-m-d',date('Y-12-31'));
                    for($i = 0;$i <= $years; $i++){
                        if($year < $end){
                            $tempEnd = \DateTime::createFromFormat('Y-m-d',$year->format('Y-m-d'));
                        }else{
                            $tempEnd = \DateTime::createFromFormat('Y-m-d',$end->format('Y-m-d'));
                            $tempEnd->modify('-'.$i.'year');
                        }
                        $periodos[] = [
                            'text' => $end->format('Y') - abs($i),
                            'intervalo' => (date('Y')-$i).'-01-01*'.$tempEnd->format('Y-m-d')
                        ];
                        $year->modify('-1 year');
                    }
                    $data['periodos'] = $periodos;
                    $data['chamadas'] = true;
                }else{
                    $data['chamadas'] = false;
                }

                foreach ($item->produtos as $produto){
                    // Manuais
                    $manuaisDb = Manual::getByServicoUrl($item->config_tecnicas->TipoServico,5);
                    $manuais = [];
                    if($manuaisDb->count() > 0){
                        foreach ($manuaisDb as $manual){
                            $manuais[] = [
                                'nome' => $manual->nome,
                                'url' => $manual->media->media_root->path.$manual->media->nome];
                        }
                    }else{
                        $manuais = false;
                    }
                    // Chamados
                    if(HasCredentials::checkRole(3)){
                        $paramsAPIChamados = Array(
                            'DOCUMENTO' => $produto->DOCUMENTO,
                            'asset' => 'SNE-E-00004-V01',//$key,
                            'status' => ["Atualizado", "Não Resolvido", "Aguardando", "Resolvido"],
                            'limit' => 3,
                            'order' => 'order by CreatedTime desc'
                        );
                        $chamadosHtt = $this->ossapi->callAPI( 'services.incident_list', $paramsAPIChamados , $this->auth->result->auth)->result->incident;
                        $chamados = [];
                        foreach ($chamadosHtt as $chamado){
                            $chamados[] = [
                                'protocolo' => $chamado->ReferenceNumber,
                                'data_abertura' => date('d/m/Y',strtotime($chamado->CreatedTime)),
                                'categoria' => $chamado->description,
                                'status' => $chamado->state,
                                'link' => url("/cliente/chamados/{$produto->DOCUMENTO}/{$chamado->ReferenceNumber}"),
                            ];
                        }
                    }else
                        $chamados = false;
                    $paramsAPI = array(
                        'documento' => $produto->DOCUMENTO,
                        'fields' => ["ID", "INSCRICAO_MUNICIPAL", "NOME", "DOCUMENTO", "RG_INSCRICAO", "FANTASIA", "EMAIL"]
                    );
                    $cliente = $this->ossapi->callAPI( 'services.get_client', $paramsAPI , $this->auth->result->auth )->result->items;
                    $data['items'][] = [
                        'manuais' => $manuais,
                        'chamados' => $chamados,
                        'id' => $key,
                        'nome' => $produto->SVC_DESC,
                        'status' => $item->config_tecnicas->StatusOper,
                        'endereco' => $item->config_tecnicas->cmdb_customer_data->CustomerAddress,
                        'velocidade' => isset($item->config_tecnicas->Velocidade) ? $item->config_tecnicas->Velocidade : '',
                        'empresa' => $cliente[0]->NOME,
                        'documento' => GetDados::getDocumentoFormated($produto->DOCUMENTO),
                        'telefones' => 'Não implementado'
                    ];
                }
            }
        }else{
            $data['items'] = [];
            $data['dados_servicos'] = false;
            $data['enderecamentos'] = false;
            $data['enderecamento_ipv6'] = false;
            $data['grafico_consumo'] = false;
            $data['dados_fibra'] = false;
            $data['dados_media'] = false;
            $data['grafico_voz'] = false;
            $data['logins'] = false;
            $data['chamadas'] = false;
        }
		$this->data = $data;
		$dado = array(
			'usuario_id' => Auth::guard('cliente_api')->user()->id,
			'ip' => $request->ip(),
			'acao' => 'Visualização dos detalhes do serviço - '.$id,
			'area' => 'Serviços'
		);
		LogUsuario::store($dado);
		return view('site/clientes/servicos/detalhe',$this->data);
	}


}


