<?php 

namespace app\utils;

use app\utils\ImpetusUtils;

class ImpetusFileManager extends ImpetusUtils
{
    /**
     * @param string $base64 Informe o base64 para conversão de base64 para arquivo
     * @param string $path Caminho do arquivo (ex.: companies/companyExample)
     * @param string $filename Nome do arquivo (ex.: important-file)
     * @param string $format Parâmetro opcional que define os tipos de arquivos permitidos podendo ser 'images' ou 'all'
     * @param int $maxSize Parâmetro opcional que define tamanho máximo de arquivo
     * @param bool $uniqueToken Parâmetro opcional que gera um ID único no nome de cada arquivo, evitando sobreposição de arquivos
     * @return array Retorna array com status e detalhamento da resposta
     */
    static public function saveFile(string $base64, string $filename, string $path, string $format = 'all', int $maxSize = 10000000, bool $uniqueToken = true): array{

        //Converts base64 to file
        $parts = explode(";base64,", $base64);
        if(!isset($parts[0]) || !isset($parts[1])){
            $response = [
                "status" => 0,
                "error" => "Conversão de arquivo falhou, verifique o base64 enviado."
            ];
            return $response; 
        }
        $fileparts = explode("/", $parts[0]);
        $filetype = $fileparts[1];
        $filebase64 = base64_decode($parts[1]);

        //Base 64 validation
        if($filebase64 == false){
            $response = [
                "status" => 0,
                "error" => "Conversão de arquivo falhou, verifique o arquivo enviado."
            ];
            return $response; 
        }

        //Size validation
        $size = strlen($filebase64);
        if($size>$maxSize){
            $size = $maxSize/1000000;
            $response = [
                "status" => 0,
                "error" => "Arquivo muito grande. Limite máximo de ".$size."mb."
            ];
            return $response; 
        }

        //Type validation
        if($format == 'image'){
            if($filetype<>'png' && $filetype<>'jpeg' && $filetype<>'jpg'){
                $response = [
                    "status" => 0,
                    "error" => "Tipo de arquivo não suportado. Arquivos suportados: pdf, png, jpg e jpeg."
                ];
                return $response;
            }
        }else{
            if($filetype<>'pdf' && $filetype<>'png' && $filetype<>'jpeg' && $filetype<>'jpg' && $filetype<>'vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $filetype<>'vnd.openxmlformats-officedocument.wordprocessingml.document' && $filetype<>'plain'){
                $response = [
                    "status" => 0,
                    "error" => "Tipo de arquivo não suportado. Arquivos suportados: pdf, png, jpg, jpeg, xlsx, doc, docx e txt."
                ];
                return $response;
            }
        }

        //Returns the correct file type for some different types
        if($filetype=='vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            $filetype = 'xlsx';
        }

        if($filetype=='vnd.openxmlformats-officedocument.wordprocessingml.document'){
            $filetype = 'docx';
        }

        if($filetype=='plain'){
            $filetype = 'txt';
        }

        //Incrementa um ID único
        if($uniqueToken == true){
            $token = "-" . uniqid();
        }else{
            $token = null;
        }

        //Format filename
        $name = ImpetusUtils::purifyString($filename, ["accentuation" => false, "specialChars" => false, "lowerCase" => true]);

        //Define path
        require "app/config/config.php";
        $file = $systemConfig['storage']['defaultPath'] . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name . $token . '.' . $filetype;

        //Create folders
        ImpetusFileManager::createFolders($path);

        //Direciona o arquivo para a pasta
        if(file_put_contents($file, $filebase64) == false){
            $response = [
                "status" => 0,
                "error" => "Falha ao direcionar arquivo."
            ];
            return $response;
        }else{
            //Remover o ponto do path para salvar no database
            $file = substr($file, 1);
            $response = [
                "status" => 1,
                "info" => "Arquivo enviado",
                "name" => $name . '.' . $filetype,
                "path" => $path
            ];
            return $response;
        }
    }

    static public function createFolders($path)
    {
        require "app/config/config.php";

        $folders = explode("/", $path);
        $currentPath = $systemConfig['storage']['defaultPath'];

        foreach($folders as $folder)
        {
            $currentPath .= DIRECTORY_SEPARATOR . $folder;
            var_dump($currentPath);
            //Cria diretório caso não exista
            if(!is_dir($currentPath)){
                mkdir($currentPath, 0751);
            }
        }
    }
}