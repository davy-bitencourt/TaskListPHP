<?php
class Task{
    public int $id;
    public string $titulo;
    public bool $status;
    public string $type;
    public string $data_limite;
    public int $user_id;

    public function __construct(
        int $id,
        string $titulo,
        bool $status,
        string $type,
        string $data_limite,
        int $user_id,
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->status = $status;
        $this->type = $type;
        $this->data_limite = $data_limite;
        $this->user_id = $user_id;
    }
}
?>