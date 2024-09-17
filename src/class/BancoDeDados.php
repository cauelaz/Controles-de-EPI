<?php
    class BancoDeDados 
    {
        //Atributos da Classe
        private $conexao;

        // Método 
        function __construct()
        {
                $this -> conexao = new PDO('mysql:host=localhost;dbname=db_epis;charset=utf8mb4', 'root', '');
        }
        public function ExecutarComando($sql, $paramentros = [])
        {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($paramentros);
        }
        public function Consultar($sql, $paramentros = [], $fecthall = false)
        {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($paramentros);
            //Verifica se o Fetch é 'TRUE'
            if($fecthall)
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else
            {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }