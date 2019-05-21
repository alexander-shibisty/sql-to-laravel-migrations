<?php namespace SqlParser;

use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Utils\Query;

use PhpMyAdmin\SqlParser\Components\Key;

use SqlParser\Types\{
    IntegerType,
    VarcharType,
    CharType,
    TextType,
    LongTextType,
    TomestampType,
    DecimalType,
    TinyIntType,
    EnumType,
    DateTimeType,
    DateType,
    DoubleType,
    MediumTextType,
    SmallIntType,
    BigIntType
};

class SqlParser {
    private $inputPath;
    private $outputPath;

    public function __construct($inputPath, $outputPath) {
        $this->inputPath = $inputPath;
        $this->outputPath = $outputPath;

        //Очищаем папку выхода
        $this->clearOutputFolder($this->outputPath);

        //Сканируем папку входа
        $inputFiles = scandir($this->inputPath);

        //Проходим по файлам внутри
        foreach($inputFiles as $inputFile) {
            if($inputFile !== '.' && $inputFile !== '..' && preg_match('/\.sql$/', $inputFile)) {
                $query = file_get_contents($this->inputPath.'/'.$inputFile);
                
                //Получаем массив данных
                $parser = new Parser($query);
                
                //Работаем с отдельными командами из файла
                $templates = $this->statements($parser->statements);

                //Создаем файлы миграций
                foreach($templates as $fileName => $fileTemplate) {
                    file_put_contents($this->outputPath.'/'.$fileName.'.php', $fileTemplate);
                }
                
            }
        }
    }

    protected function statements(array $statements): array {
        $tempaltes = [];
        
        foreach($statements as $statement) {
            //dd($statement);
            $parametres = [
                '{{className}}' => str_replace('_', '', $statement->name->table),
                '{{tableName}}' => $statement->name->table,
                '{{fields}}' => $this->generateFieldsString($statement->fields),
            ];
            
            $keys = [];
            $options = [];
            
            foreach($parametres as $key => $option) {
                $keys[] = $key;
                $options[] = $option;
            }
    
            $template = file_get_contents(__DIR__.'/template/greate.template');
            
            $result = str_replace($keys, $options, $template);

            $tempaltes[$this->generateFileName($statement->name->table)] = $result;
        }

        return $tempaltes;
    }

    protected function generateFieldsString(array $fields): string {
        $fieldsString = '';

        foreach($fields as $field) {
            //Numbers
            if($field->type->name === 'INT') {
                $fieldsString .= new IntegerType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'BIGINT') {
                $fieldsString .= new BigIntType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'TINYINT') {
                $fieldsString .= new TinyIntType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'SMALLINT') {
                $fieldsString .= new SmallIntType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'DECIMAL') {
                $fieldsString .= new DecimalType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'DOUBLE') {
                $fieldsString .= new DoubleType($field, $this->getKeysIfExists($fields, $field->name));
            }
            
            //String
            elseif($field->type->name === 'VARCHAR') {
                $fieldsString .= new VarcharType($field, $this->getKeysIfExists($fields, $field->name));
            } elseif($field->type->name === 'CHAR') {
                $fieldsString .= new CharType($field, $this->getKeysIfExists($fields, $field->name));
            }
            
            //Array
            elseif($field->type->name === 'ENUM') {
                $fieldsString .= new EnumType($field, $this->getKeysIfExists($fields, $field->name));
            }

            //Text
            elseif($field->type->name === 'TEXT') {
                $fieldsString .= new TextType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'LONGTEXT') {
                $fieldsString .= new LongTextType($field, $this->getKeysIfExists($fields, $field->name));
            }
            elseif($field->type->name === 'MEDIUMTEXT') {
                $fieldsString .= new MediumTextType($field, $this->getKeysIfExists($fields, $field->name));
            }

            //Date and time
            elseif($field->type->name === 'TIMESTAMP') {
                $fieldsString .= new TomestampType($field, $this->getKeysIfExists($fields, $field->name));
            } 
            elseif($field->type->name === 'DATETIME') {
                $fieldsString .= new DateTimeType($field, $this->getKeysIfExists($fields, $field->name));
            } 
            elseif($field->type->name === 'DATE') {
                $fieldsString .= new DateType($field, $this->getKeysIfExists($fields, $field->name));
            }
            else {
                print_r($field);
            }
        }

        return $fieldsString;
    }

    protected function getKeysIfExists($fields, string $fieldName): array {
        $keys = [];
        foreach($fields as $field) {
            if(isset($field->key) && is_object($field->key) && get_class($field->key) === Key::class) {
                if($field->key->columns[0]['name'] === $fieldName) {
                    $keys[] = $field;
                }
            }
        }

        return $keys;
    }

    private function generateFileName(string $table): string {
        return date('Y_m_d_His').'_'.$table;
    }

    private function clearOutputFolder() {
        $outputFiles = scandir($this->outputPath);

        foreach($outputFiles as $outputFile) {
            if($outputFile !== '.' && $outputFile !== '..') {
                @unlink($this->outputPath . '/' . $outputFile);
            }
        }
    }

}