<?php
    include_once('Ambiente.php');
    (new DotEnv(__DIR__ . '/../.env'))->load();   //Carregando os dados de acesso do arquivo env

    class BancoDeDados 
    {
        //Atributos da Classe
        private $conexao;

        function __construct()
        {  
            $this -> conexao = new PDO('mysql:host='.getenv("DB_HOST").';dbname='.getenv("DB_NAME").';charset=utf8mb4', getenv("DB_USER"), getenv("DB_PASSWORD"));
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