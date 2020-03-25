<?php

namespace Component\Log\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class SqlFormatter extends NormalizerFormatter
{
    private const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    protected string $format;
    protected bool $allowInlineLineBreaks;
    protected bool $ignoreEmptyContextAndExtra;

    /**
     * @param string $format                     The format of the message
     * @param string $dateFormat                 The format of the timestamp: one supported by DateTime::format
     * @param bool   $allowInlineLineBreaks      Whether to allow inline line breaks in log entries
     * @param bool   $ignoreEmptyContextAndExtra
     */
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null,
        bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false
    )
    {
        $this->format = $format ?: static::SIMPLE_FORMAT;
        $this->allowInlineLineBreaks = $allowInlineLineBreaks;
        $this->ignoreEmptyContextAndExtra = $ignoreEmptyContextAndExtra;
        parent::__construct($dateFormat);
    }


    /**
     * Formats a log record.
     *
     * @param  array $records A record to format
     * @return mixed The formatted record
     */
    public function format(array $records)
    {
        $formattedRecords = parent::format($records);
        $contextData = $formattedRecords['context'];

        $context = $contextData['Class'] . ' | ' . $contextData['Pool']
            . ' | ' . $contextData['Time'] . ' | ' . $contextData['RowsAffected'];

        $output = $this->format;
        $output = str_replace('%context%', $context, $output);

        foreach ($formattedRecords as $formattedRecord => $val) {
            if (false !== strpos($output, '%'.$formattedRecord.'%')) {
                $output = str_replace('%'.$formattedRecord.'%', $this->stringify($val), $output);
            }
        }

        return $output;
    }

    public function stringify($value): string
    {
        return $this->replaceNewlines($this->convertToString($value));
    }

    protected function convertToString($data): string
    {
        $stringValue = null;

        if (null === $data || is_bool($data)) {
            $stringValue = var_export($data, true);
        } elseif (is_scalar($data)) {
            $stringValue = (string) $data;
        } elseif (PHP_VERSION_ID >= 50400) {
            $stringValue = $this->toJson($data, true);
        } else {
            $stringValue = str_replace('\\/', '/', json_encode($data));
        }

        return $stringValue;
    }

    protected function replaceNewlines(string $str): string
    {
        if ($this->allowInlineLineBreaks) {
            return $str;
        }

        return str_replace(array("\r\n", "\r", "\n"), ' ', $str);
    }
}
