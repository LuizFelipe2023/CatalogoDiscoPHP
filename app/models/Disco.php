<?php

class Disco
{
    public ?int $id;
    public string $nome;
    public string $genero;
    public string $artista;
    public float $avaliacao;
    public string $formato;
    public string $status;
    public int $user_id;
    public ?string $capa;

    public function __construct(
        string $nome,
        string $genero,
        string $artista,
        float $avaliacao,
        string $formato,
        string $status,
        int $user_id,
        ?int $id = null,
        ?string $capa = null 
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->genero = $genero;
        $this->artista = $artista;
        $this->avaliacao = $avaliacao;
        $this->formato = $formato;
        $this->status = $status;
        $this->user_id = $user_id;
        $this->capa = $capa;
    }

    public static function fromArray(array $data): Disco
    {
        return new Disco(
            $data['nome'],
            $data['genero'],
            $data['artista'],
            (float)$data['avaliacao'],
            $data['formato'],
            $data['status'],
            (int)$data['user_id'],
            $data['id'] ?? null,
            $data['capa'] ?? null 
        );
    }
}
?>