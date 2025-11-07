<?php
class Task{
    public function __construct(
        int $id,
        string $titulo,
        string $status,
        string $data_limite,
        int $user_id,
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->status = $status;
        $this->data_limite = $data_limite;
        $this->user_id = $user_id;
    }
}
?>