<?php


namespace App\Jobs\Export;


use App\Jobs\Job;
use Common\Service\Common\Model\RequestMeta;
use Common\Service\Common\Model\TableExport;
use Common\Service\Converter\Payload\ConvertRequest;
use Common\Service\Converter\Payload\ConvertResponse;

abstract class ExportJob extends Job
{
    /**
     * @var string
     */
    protected string $uuid;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * @var RequestMeta
     */
    protected RequestMeta $meta;

    /**
     * @param string $uuid
     * @param array $parameters
     * @param RequestMeta $meta
     */
    public function __construct(string $uuid, array $parameters, RequestMeta $meta)
    {
        $this->uuid       = $uuid;
        $this->meta       = $meta;
        $this->parameters = $parameters;
    }

    /**
     * Запуск выполнения джобы
     */
    public function handle()
    {
        ini_set('memory_limit', '4096M');

        [$rows, $headers] = $this->getRowsAndHeaders($this->meta);

        $this->saveExport($rows, $headers);
    }

    protected function saveExport(array $rows, array $headers)
    {
        $response = $this->convertToXls($headers, $rows);

        if ($response->success) {
            $tableExport = new TableExport;
            $tableExport->uuid = $this->uuid;
            $tableExport->save($response->payload->content);
        }
    }

    protected function convertToXls($header, $rows): \Common\Service\Common\Model\Response
    {
        $content = '<table>';
        $content .= '<thead><tr>' . collect($header)->map(function ($text) {
                return '<th>' . $text . '</th>';
            })->implode('') . '</tr></thead>';

        $content .= '<tbody>' . collect($rows)->map(function ($row) {
                return '<tr>' . collect($row)->map(function ($text) {
                        return '<th>' . $text . '</th>';
                    })->implode('') . '</tr>';
            })->implode('') . '</tbody>';
        $content .= '</table>';

        $payload = new ConvertRequest;
        $payload->content = $content;

        return exchange()->request('com.rnis.converter.action.convert.html_to_xls', $payload)
            ->withSystemAuth()
            ->withTimeout(999)
            ->send(ConvertResponse::class);
    }

    abstract protected function getRowsAndHeaders();
}
