<?php
    include_once('Ambiente.php');
    (new DotEnv(__DIR__ . '/../.env'))->load();   //Carregando os dados de acesso do arquivo env

    class BancoDeDados 
    {
        //Atributos da Classe
        private $conexao;

        function __construct()
        {  
            $this -> conexao = new PDO('mysql:host='.getenv("DB_HOST").';dbname='.getenv("DB_NAME").';port='.getenv("DB_PORT").';charset=utf8mb4', getenv("DB_USER"), getenv("DB_PASSWORD"));
        }
        public function ExecutarComando($sql, $parametros = [])
        {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($parametros);
        }
        public function Consultar($sql, $parametros = [], $fecthall = false)
        {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($parametros);
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
        public function getLastInsertId() {
            return $this->conexao->lastInsertId();
        }

        public function ExecutarRetornandoId($sql, $parametros = []){
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($parametros);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }