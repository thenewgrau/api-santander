<?php
class transacaoDto
{

    private ?string $id_transacao = null;
    private ?string $valor = null;
    private ?string $data_hora_transferencia = null;
    private ?string $contaDestino = null;


    /**
     * Get the value of id_transacao
     */ 
    public function getId_transacao()
    {
        return $this->id_transacao;
    }

    /**
     * Set the value of id_transacao
     *
     * @return  self
     */ 
    public function setId_transacao($id_transacao)
    {
        $this->id_transacao = $id_transacao;

        return $this;
    }

    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of data_hora_transferencia
     */ 
    public function getData_hora_transferencia()
    {
        return $this->data_hora_transferencia;
    }

    /**
     * Set the value of data_hora_transferencia
     *
     * @return  self
     */ 
    public function setData_hora_transferencia($data_hora_transferencia)
    {
        $this->data_hora_transferencia = $data_hora_transferencia;

        return $this;
    }

    /**
     * Get the value of contaDestino
     */ 
    public function getContaDestino()
    {
        return $this->contaDestino;
    }

    /**
     * Set the value of contaDestino
     *
     * @return  self
     */ 
    public function setContaDestino($contaDestino)
    {
        $this->contaDestino = $contaDestino;

        return $this;
    }
}