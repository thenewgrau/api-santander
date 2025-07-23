<?php

namespace App\Dto;

class TransacaoDto
{
    private ?string $valor = null;
    private ?string $contaOrigemId = null;
    private ?string $contaDestinoId = null;

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
     * Get the value of ContaOrigemId
     */ 
    public function getContaOrigemId()
    {
        return $this->contaOrigemId;
    }

    /**
     * Set the value of ContaOrigemId
     *
     * @return  self
     */ 
    public function setContaOrigemId($contaOrigemId)
    {
        $this->contaOrigemId = $contaOrigemId;

        return $this;
    }
    
    /**
     * Get the value of contaDestinoId
     */ 
    public function getContaDestinoId()
    {
        return $this->contaDestinoId;
    }

    /**
     * Set the value of contaDestinoId
     *
     * @return  self
     */ 
    public function setContaDestinoId($contaDestinoId)
    {
        $this->contaDestinoId = $contaDestinoId;

        return $this;
    }

}