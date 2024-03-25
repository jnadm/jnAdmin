<?php
namespace App\Library;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterFactory;
use Box\Spout\Writer\Exception\WriterNotOpenedException;

/**
 * @desc   box spout 导入导出数据到EXCEL
 * @author flyer
 * @date   2024/3/5
 **/
class ExcelSpout
{
    /**
     * @desc   导出数据
     * @param string $path 路径
     * @param array $header 标题
     * @param array $data 数据
     * @param string $type 导出格式
     * @throws IOException
     * @author flyer
     * @date   2024/3/5
     **/
    public function export(string $path, array $header, array $data, string $type = Type::XLSX): bool
    {
        try{
            $writer = WriterFactory::createFromType($type);
            $writer->openToFile($path);
            $headerRow = WriterEntityFactory::createRowFromArray($header);
            // 加粗样式
            $style = (new StyleBuilder())->setFontBold()->build();
            // 给标题行添加样式
            foreach ($headerRow->getCells() as $cell) {
                $cell->setStyle($style);
            }
            $writer->addRow($headerRow);
            foreach ($data as $row) {
                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }
            $writer->close();
        }catch (IOException|WriterNotOpenedException|UnsupportedTypeException $e) {
            throw new IOException($e->getMessage());
        }

        return true;
    }

    /**
     * 导入
     * @param string $path 路径
     * @param int $startRow 数据开始行数
     * @throws IOException
     * @author flyer
     * @date   2024/3/5
     */
    public function import(string $path, int $startRow = 1): array
    {
        try {
            $reader = ReaderFactory::createFromType(Type::XLSX);
            $reader->open($path);

            $data = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $index => $row) {
                    if ($index >= $startRow) {
                        $data[] = $this->parseRow($row);
                    }
                }
            }
            $reader->close();
            return $data;
        } catch (UnsupportedTypeException|ReaderNotOpenedException|IOException $e) {
            throw new IOException($e->getMessage());
        }
    }

}


