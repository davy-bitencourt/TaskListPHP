<?php
class User{
    public function __construct(
        int $id,
        string $username,
        string $senha,
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->senha = $senha;
    }
}
?>